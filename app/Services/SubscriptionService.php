<?php

namespace App\Services;

use App\Actions\Customer\StoreCustomerAction;
use App\Actions\Subscription\CreateSubscriptionAction;
use App\Constants\PlaceToPayStatus;
use App\Constants\SubscriptionStatus;
use App\Contracts\PlaceToPayServiceInterface;
use App\Contracts\SubscriptionServiceInterface;
use App\Jobs\CollectSubscriptionPaymentJob;
use App\Mail\SubscriptionCreatedMail;
use App\Models\Microsite;
use App\Models\Plan;
use App\Models\Subscription;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;

class SubscriptionService implements SubscriptionServiceInterface
{
    private PlaceToPayServiceInterface $placeToPayService;

    public function __construct(PlaceToPayServiceInterface $placeToPayService)
    {
        $this->placeToPayService = $placeToPayService;
    }

    public function createSubscription(Plan $plan, Microsite $microsite, array $data): array
    {
        $customerData = (new StoreCustomerAction())->execute($data);

        $subscription = (new CreateSubscriptionAction())->execute($plan, $microsite, $customerData, $data['additional_data']);

        $result = $this->placeToPayService->createSubscription($customerData, $subscription);

        if (!$result['success']) {
            $subscription->update([
                'request_id' => $result['data']['requestId'],
                'status' => SubscriptionStatus::INACTIVE->value,
                'status_message' => $result['message'],
            ]);

            return [
                'success' => false,
            ];
        }

        $resultData = $result['data'];

        $subscription->update([
            'request_id' => $resultData['requestId'],
            'status_message' => $resultData['status']['message'],
        ]);

        return [
            'success' => true,
            'url' => $resultData['processUrl'],
        ];
    }

    public function checkSubscription(Subscription $subscription): bool
    {
        $cacheKey = 'subscription_checked_' . $subscription->id;
        $isRecentlyChecked = Cache::get($cacheKey);

        if ($isRecentlyChecked || $subscription->status !== SubscriptionStatus::PENDING->value) {
            return true;
        }

        $result = $this->placeToPayService->checkSession($subscription->request_id);

        if (!$result['success']) {
            return false;
        }

        $this->updateSubscription($subscription, $result['data']);

        Cache::put($cacheKey, true, now()->addMinutes(10));

        return true;
    }

    private function updateSubscription(Subscription $subscription, array $dataResponse): void
    {
        $subscriptionStatus = $dataResponse['status'];

        $mappedStatus = $this->mapPlaceToPayStatusToSubscriptionStatus(PlaceToPayStatus::from($subscriptionStatus['status']));

        $subscription->update([
            'status' => $mappedStatus,
            'status_message' => $subscriptionStatus['message'],
        ]);

        if ($mappedStatus === SubscriptionStatus::ACTIVE->value) {
            $subscriptionInstrument = $dataResponse['subscription']['instrument'];

            $subscription->update([
                'token' => encrypt($subscriptionInstrument[0]['value']),
                'subtoken' => encrypt($subscriptionInstrument[1]['value']),
            ]);

            CollectSubscriptionPaymentJob::dispatch($subscription->id);

            Mail::to($subscription->customer->email)
                ->queue(new SubscriptionCreatedMail($subscription));
        }
    }

    private function mapPlaceToPayStatusToSubscriptionStatus(PlaceToPayStatus $status): string
    {
        return match ($status) {
            PlaceToPayStatus::APPROVED, PlaceToPayStatus::APPROVED_PARTIAL => SubscriptionStatus::ACTIVE->value,
            PlaceToPayStatus::PENDING => SubscriptionStatus::PENDING->value,
            default => SubscriptionStatus::INACTIVE->value,
        };
    }

    public function cancelSubscription(Subscription $subscription): bool
    {
        $result = $this->placeToPayService->cancelSubscription($subscription->token);

        if (!$result['success']) {
            return false;
        }

        $subscription->update([
            'status' => SubscriptionStatus::CANCELED->value,
        ]);

        return true;
    }
}

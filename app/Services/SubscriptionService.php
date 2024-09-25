<?php

namespace App\Services;

use App\Actions\Customer\StoreCustomerAction;
use App\Constants\PlaceToPayStatus;
use App\Constants\SubscriptionStatus;
use App\Contracts\PlaceToPayServiceInterface;
use App\Contracts\SubscriptionServiceInterface;
use App\Models\Microsite;
use App\Models\Plan;
use App\Models\Subscription;
use DateInterval;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

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

        $start_date = now();
        $end_date = now()->add(DateInterval::createFromDateString("{$plan->total_duration} {$plan->time_unit->value}"));

        /** @var Subscription $subscription */
        $subscription = Subscription::create([
            'customer_id' => $customerData->id,
            'plan_id' => $plan->id,
            'start_date' => $start_date,
            'end_date' => $end_date,
            'reference' => date('ymdHis') . '-' . strtoupper(Str::random(4)),
            'description' => $customerData->name . ' ' . $plan->id,
            'currency' => $microsite->payment_currency->value,
            'additional_data' => $data['additional_data'],
        ]);

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

        if ($subscriptionStatus['status'] !== PlaceToPayStatus::APPROVED->value) {
            $subscription->update([
                'status' => SubscriptionStatus::INACTIVE->value,
                'status_message' => $subscriptionStatus['message'],
            ]);
            return;
        }

        $subscriptionInstrument = $dataResponse['subscription']['instrument'];

        $subscription->update([
            'status' => SubscriptionStatus::ACTIVE->value,
            'status_message' => $subscriptionStatus['message'],
            'token' => encrypt($subscriptionInstrument[0]['value']),
            'subtoken' => encrypt($subscriptionInstrument[1]['value']),
        ]);
    }

    public function cancelSubscription(Subscription $subscription): bool
    {
        $result = $this->placeToPayService->cancelSubscription(decrypt($subscription->token));

        if (!$result['success']) {
            return false;
        }

        $subscription->update([
            'status' => SubscriptionStatus::INACTIVE->value,
        ]);

        return true;
    }
}

<?php

namespace App\Services;

use App\Actions\Customer\StoreCustomerAction;
use App\Constants\PlaceToPayStatus;
use App\Constants\SubscriptionStatus;
use App\Contracts\PlaceToPayServiceInterface;
use App\Contracts\SubscriptionServiceInterface;
use App\Models\Subscription;
use Illuminate\Support\Str;

class SubscriptionService implements SubscriptionServiceInterface
{

    private PlaceToPayServiceInterface $placeToPayService;

    public function __construct(PlaceToPayServiceInterface $placeToPayService)
    {
        $this->placeToPayService = $placeToPayService;
    }

    public function createSubscription(array $subscriptionData): array
    {
        $customerData = (new StoreCustomerAction())->execute($subscriptionData);

        $start_date = now();
        $end_date = now()->add($subscriptionData['total_duration'], $subscriptionData['time_unit']);

        /** @var Subscription $subscription */
        $subscription = Subscription::create([
            'customer_id' => $customerData->id,
            'plan_id' => $subscriptionData['plan_id'],
            'start_date' => $start_date,
            'end_date' => $end_date,
            'reference' => date('ymdHis') . '-' . strtoupper(Str::random(4)),
            'description' => $customerData->name . ' ' . $subscriptionData['plan_id'],
            'currency' => $subscriptionData['currency'],
            'additional_data' => $subscriptionData['additional_data'],
        ]);

        $result = $this->placeToPayService->createSubscription($customerData, $subscription);

        $dataResponse = $result->json();
        if (!$result->ok()) {
            $subscription->update([
                'request_id' => $dataResponse['requestId'],
                'status' => SubscriptionStatus::INACTIVE->value,
                'status_message' => $dataResponse['status']['message'],
            ]);

            return [
                'success' => false,
                'message' => $dataResponse['status']['message'],
            ];
        }

        $subscription->update([
            'request_id' => $dataResponse['requestId'],
            'status_message' => $dataResponse['status']['message'],
        ]);

        return [
            'success' => $result->ok(),
            'url' => $result['processUrl'] ?? null,
            'message' => $dataResponse['status']['message'] ?? null,
        ];
    }

    public function checkSubscription(Subscription $subscription): array
    {
        $result = $this->placeToPayService->checkSubscription($subscription->request_id);
        $dataResponse = $result->json();

        if ($result->ok()) {
            $this->updateSubscription($subscription, $dataResponse);

            return [
                'success' => true,
                'message' => $dataResponse['status']['message'],
                'subscription' => $subscription,
            ];
        } else {
            return [
                'success' => false,
                'message' => $dataResponse['status']['message'],
            ];
        }

    }

    private function updateSubscription(Subscription $subscription, array $dataResponse): void
    {
        $subscriptionStatus = $dataResponse['status'];

        if ($subscriptionStatus['status'] === PlaceToPayStatus::APPROVED->value) {
            $subscriptionInstrument = $dataResponse['subscription']['instrument'];

            $subscription->update([
                'status' => SubscriptionStatus::ACTIVE->value,
                'status_message' => $subscriptionStatus['message'],
                'token' => $subscriptionInstrument[0]['value'],
                'subtoken' => $subscriptionInstrument[1]['value'],
            ]);

        } else {
            $subscription->update([
                'status' => SubscriptionStatus::INACTIVE->value,
                'status_message' => $subscriptionStatus['message'],
            ]);
        }
    }
}

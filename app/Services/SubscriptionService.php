<?php

namespace App\Services;

use App\Constants\SubscriptionStatus;
use App\Contracts\PlaceToPayServiceInterface;
use App\Contracts\SubscriptionServiceInterface;
use App\Models\Customer;
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
        /** @var Customer $customer */
        $customer = Customer::query()->firstOrCreate(
            ['document_number' => $subscriptionData['document_number']],
            [
                'name' => $subscriptionData['name'],
                'last_name' => $subscriptionData['last_name'],
                'document_type' => $subscriptionData['document_type'],
                'document_number' => $subscriptionData['document_number'],
                'phone' => $subscriptionData['phone'],
                'email' => $subscriptionData['email'],
            ]
        );

        $customer->subscriptions()->attach($subscriptionData['subscription_id'], [
            'start_date' => now(),
            'reference' => date('ymdHis') . '-' . strtoupper(Str::random(4)),
            'description' => $customer->name . ' ' . $subscriptionData['subscription_id'],
            'currency' => $subscriptionData['currency'],
            'additional_data' => json_encode($subscriptionData['additional_data']),
        ]);

        $subscriptionPivot = $customer->subscriptions()
            ->where('subscription_id', $subscriptionData['subscription_id'])
            ->first()
            ->pivot;

        $result = $this->placeToPayService->createSubscription($customer, $subscriptionPivot);

        if (!$result->ok()) {
            $customer->subscriptions()->updateExistingPivot($subscriptionData['subscription_id'], [
                'request_id' => $result->json()['requestId'],
                'status' => SubscriptionStatus::INACTIVE->value,
                'status_message' => $result->json()['status']['message'],
            ]);

            return [
                'success' => false,
                'message' => $result->json()['status']['message'],
            ];
        }

        $customer->subscriptions()->updateExistingPivot($subscriptionData['subscription_id'], [
            'request_id' => $result->json()['requestId'],
            'status' => SubscriptionStatus::ACTIVE->value,
            'status_message' => $result->json()['status']['message'],
        ]);

        return [
            'success' => $result->ok(),
            'url' => $result['processUrl'] ?? null,
            'message' => $result->json()['status']['message'] ?? null,
        ];
    }

    public function checkSubscription(Subscription $subscription): array
    {
        // TODO: Implement checkSubscription() method.
    }
}

<?php

namespace App\Services;

use App\Constants\SubscriptionStatus;
use App\Contracts\PlaceToPayServiceInterface;
use App\Contracts\SubscriptionServiceInterface;
use App\Models\Customer;
use App\Models\CustomerSubscription;
use App\Models\Subscription;
use DateInterval;
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

        $start_date = now();
        $end_date = now()->add(DateInterval::createFromDateString("{$subscriptionData['total_duration']} {$subscriptionData['time_unit']}"));

        /** @var CustomerSubscription $subscriptionPivot */
        $subscriptionPivot = CustomerSubscription::query()->firstOrCreate(
            [
                'customer_id' => $customer->id,
                'subscription_id' => $subscriptionData['subscription_id'],
            ],
            [
                'start_date' => $start_date,
                'end_date' => $end_date,
                'reference' => date('ymdHis') . '-' . strtoupper(Str::random(4)),
                'description' => $customer->name . ' ' . $subscriptionData['subscription_id'],
                'currency' => $subscriptionData['currency'],
                'additional_data' => $subscriptionData['additional_data'],
            ]
        );

        $result = $this->placeToPayService->createSubscription($customer, $subscriptionPivot);

        $dataResponse = $result->json();
        if (!$result->ok()) {
            $subscriptionPivot->update([
                'request_id' => $dataResponse['requestId'],
                'status' => SubscriptionStatus::INACTIVE->value,
                'status_message' => $dataResponse['status']['message'],
            ]);

            return [
                'success' => false,
                'message' => $dataResponse['status']['message'],
            ];
        }

        $subscriptionPivot->update([
            'request_id' => $dataResponse['requestId'],
            'status' => SubscriptionStatus::ACTIVE->value,
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
        // TODO: Implement checkSubscription() method.
    }
}

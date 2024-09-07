<?php

namespace App\Contracts;

use App\Models\CustomerSubscription;

interface SubscriptionServiceInterface
{
    public function createSubscription(array $subscriptionData): array;

    public function checkSubscription(CustomerSubscription $customerSubscription): array;

}

<?php

namespace App\Contracts;

use App\Models\Subscription;

interface SubscriptionServiceInterface
{
    public function createSubscription(array $subscriptionData): array;

    public function checkSubscription(Subscription $subscription): array;

}

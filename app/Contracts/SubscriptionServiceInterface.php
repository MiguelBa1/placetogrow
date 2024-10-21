<?php

namespace App\Contracts;

use App\Models\Microsite;
use App\Models\Plan;
use App\Models\Subscription;

interface SubscriptionServiceInterface
{
    public function createSubscription(Plan $plan, Microsite $microsite, array $data): array;

    public function checkSubscription(Subscription $subscription): bool;

    public function cancelSubscription(Subscription $subscription): bool;
}

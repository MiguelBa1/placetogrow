<?php

namespace App\Contracts;

use App\Models\Customer;
use App\Models\Payment;
use App\Models\Subscription;

interface PlaceToPayServiceInterface
{
    public function createPayment(Customer $customer, Payment $payment): array;

    public function createSubscription(Customer $customer, Subscription $subscription): array;

    public function cancelSubscription(string $subscriptionToken): array;

    public function checkSession(string $sessionId): array;
}

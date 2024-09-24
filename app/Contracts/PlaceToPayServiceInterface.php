<?php

namespace App\Contracts;

use App\Models\Customer;
use App\Models\Payment;
use App\Models\Subscription;
use Illuminate\Http\Client\Response;

interface PlaceToPayServiceInterface
{
    public function createPayment(Customer $customer, Payment $payment): Response;

    public function checkPayment(string $sessionId): Response;

    public function createSubscription(Customer $customer, Subscription $subscription): Response;

    public function checkSubscription(string $sessionId): Response;
}

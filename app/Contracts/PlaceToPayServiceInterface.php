<?php

namespace App\Contracts;

use App\Models\Customer;
use App\Models\CustomerSubscription;
use App\Models\Payment;
use Illuminate\Http\Client\Response;

interface PlaceToPayServiceInterface
{
    public function createPayment(Customer $customer, Payment $payment): Response;

    public function checkPayment(string $sessionId): Response;

    public function createSubscription(Customer $customer, CustomerSubscription $subscriptionPivot): Response;

    public function checkSubscription(string $sessionId): Response;
}

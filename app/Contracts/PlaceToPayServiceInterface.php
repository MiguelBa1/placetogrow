<?php

namespace App\Contracts;

use App\Models\Customer;
use App\Models\Payment;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Http\Client\Response;

interface PlaceToPayServiceInterface
{
    public function createPayment(Customer $customer, Payment $payment): Response;

    public function checkPayment(string $sessionId): Response;

    public function createSubscription(Customer $customer, Pivot $subscriptionPivot): Response;

    public function checkSubscription(string $subscriptionId): Response;
}

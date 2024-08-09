<?php

namespace App\Contracts;

use App\Models\Customer;
use App\Models\Payment;
use Illuminate\Http\Client\Response;

interface PlaceToPayServiceInterface
{
    public function createPayment(Customer $customer, Payment $payment): Response;

    public function checkPayment(string $sessionId): Response;
}

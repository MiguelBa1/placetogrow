<?php

namespace App\Contracts;

use App\Models\Payment;

interface PaymentServiceInterface
{
    public function createPayment(array $paymentData): array;

    public function checkPayment(Payment $payment): array;

}

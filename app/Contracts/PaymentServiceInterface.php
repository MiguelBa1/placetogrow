<?php

namespace App\Contracts;

use App\Models\Payment;

interface PaymentServiceInterface
{
    public function createPayment(array $paymentData);

    public function checkPayment(Payment $payment);

}

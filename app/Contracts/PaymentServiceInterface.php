<?php

namespace App\Contracts;

use App\Models\Microsite;
use App\Models\Payment;

interface PaymentServiceInterface
{
    public function createPayment(Microsite $microsite, array $paymentData): array;

    public function checkPayment(Payment $payment): array;

}

<?php

namespace App\Contracts;

use Illuminate\Http\Request;

interface PaymentServiceInterface
{
    public function createPayment(Request $request);

    public function checkPayment(string $reference);

    public function createPaymentRecord($request, $paymentReference, $response);

    public function updatePayment($paymentReference, $response);
}

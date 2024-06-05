<?php

namespace App;

use Illuminate\Http\Request;

interface PaymentService
{
    public function createPayment(Request $request);

    public function checkPayment(string $reference);

    public function createUser($request, $paymentReference, $response);

    public function updatePayment($paymentReference, $response);
}

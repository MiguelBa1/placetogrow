<?php

namespace App\Contracts;

use App\Models\Microsite;

interface PaymentServiceInterface
{
    public function createPayment(array $paymentData, Microsite $microsite);

    public function checkPayment(string $reference, string $micrositeSlug);

    public function updatePayment(string $paymentReference, array $response);
}

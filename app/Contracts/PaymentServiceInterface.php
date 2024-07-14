<?php

namespace App\Contracts;

use App\Models\Microsite;

interface PaymentServiceInterface
{
    public function createPayment(array $paymentData, string $ipAddress, string $userAgent, Microsite $microsite);

    public function checkPayment(string $reference, string $micrositeSlug);

    public function updatePayment(string $paymentReference, array $response);
}

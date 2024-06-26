<?php

namespace App\Contracts;

interface PaymentServiceInterface
{
    public function createPayment(array $paymentData, string $ipAddress, string $userAgent, int $micrositeId);

    public function checkPayment(string $reference, int $micrositeId);

    public function createPaymentRecord(array $paymentData, string $paymentReference, array $response);

    public function updatePayment(string $paymentReference, array $response);
}

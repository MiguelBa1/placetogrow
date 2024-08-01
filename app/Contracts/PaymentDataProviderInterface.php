<?php

namespace App\Contracts;

interface PaymentDataProviderInterface
{
    public function getPaymentData(array $data): array;
}

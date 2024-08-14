<?php

namespace App\Contracts;

use Illuminate\Database\Eloquent\Collection;

interface PaymentDataProviderInterface
{
    public function getPaymentData(array $data, Collection $fields): array;
}

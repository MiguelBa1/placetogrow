<?php

namespace App\Factories;

use App\Constants\MicrositeType;
use App\Contracts\PaymentDataProviderInterface;
use App\Services\Payment\BasicPaymentDataProvider;
use App\Services\Payment\InvoicePaymentDataProvider;
use InvalidArgumentException;

class PaymentDataProviderFactory
{
    public function create(MicrositeType $micrositeType): PaymentDataProviderInterface
    {
        return match ($micrositeType) {
            MicrositeType::BASIC => new BasicPaymentDataProvider(),
            MicrositeType::INVOICE => new InvoicePaymentDataProvider(),
            default => throw new InvalidArgumentException("Unsupported microsite type: $micrositeType->value"),
        };
    }
}

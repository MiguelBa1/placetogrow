<?php

namespace App\Constants;

use App\Concerns\EnumToArray;

enum PaymentStatus: string
{
    use EnumToArray;

    case FAILED = 'FAILED';
    case OK = 'OK';
    case PENDING = 'PENDING';
    case APPROVED = 'APPROVED';
    case REJECTED = 'REJECTED';
    case UNKNOWN = 'UNKNOWN';

    public static function toSelectArray(): array
    {
        return array_map(fn ($case) => [
            'label' => __("payment.statuses.{$case->value}"),
            'value' => $case->value
        ], self::cases());
    }
}

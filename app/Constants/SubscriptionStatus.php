<?php

namespace App\Constants;

use App\Concerns\EnumToArray;

enum SubscriptionStatus: string
{
    use EnumToArray;

    case ACTIVE = 'ACTIVE';
    case INACTIVE = 'INACTIVE';
    case CANCELED = 'CANCELED';
    case PENDING = 'PENDING';

    public static function toSelectArray(): array
    {
        return array_map(fn ($case) => [
            'label' => __("subscription.statuses.{$case->value}"),
            'value' => $case->value
        ], self::cases());
    }
}

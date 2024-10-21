<?php

namespace App\Constants;

enum LateFeeType: string
{
    case FIXED = 'fixed';
    case PERCENTAGE = 'percentage';

    public static function toSelectArray(): array
    {
        return array_map(fn ($case) => [
            'label' => __("late_fee_types.{$case->value}"),
            'value' => $case->value
        ], self::cases());
    }
}

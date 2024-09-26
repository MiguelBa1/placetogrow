<?php

namespace App\Constants;

use App\Concerns\EnumToArray;

enum TimeUnit: string
{
    use EnumToArray;

    case DAYS = 'days';
    case MONTHS = 'months';
    case YEARS = 'years';

    public static function toSelectArray(): array
    {
        return array_map(fn ($case) => [
            'label' => trans_choice("time_units.{$case->value}", 2),
            'value' => $case->value
        ], self::cases());
    }
}

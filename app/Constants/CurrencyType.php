<?php

namespace App\Constants;

enum CurrencyType: string
{
    case COP = 'COP';
    case USD = 'USD';

    public static function toSelectArray(): array
    {
        return array_map(fn ($case) => [
            'label' => __("currency_types.{$case->value}"),
            'value' => $case->value
        ], self::cases());
    }
}

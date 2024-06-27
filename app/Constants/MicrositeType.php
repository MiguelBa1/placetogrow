<?php

namespace App\Constants;

enum MicrositeType: string
{
    case INVOICE = 'invoice';
    case SUBSCRIPTION = 'subscription';
    case BASIC = 'basic';

    public static function toSelectArray(): array
    {
        return array_map(fn ($case) => [
            'label' => __("microsite_types.{$case->value}"),
            'value' => $case->value
        ], self::cases());
    }
}

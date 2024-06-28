<?php

namespace App\Constants;

enum MicrositeType: string
{
    case INVOICE = 'invoice';
    case SUBSCRIPTION = 'subscription';
    case BASIC = 'basic';

    public function defaultExpirationDays(): ?int
    {
        return match($this) {
            self::INVOICE => 45,
            self::SUBSCRIPTION => 30,
            self::BASIC => null,
        };
    }


    public static function toSelectArray(): array
    {
        return array_map(fn ($case) => [
            'label' => __("microsite_types.{$case->value}"),
            'value' => $case->value
        ], self::cases());
    }
}

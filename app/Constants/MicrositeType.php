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
            self::SUBSCRIPTION, self::BASIC => null,
        };
    }

    public function defaultSettings(): array
    {
        return match($this) {
            self::INVOICE => [
                'late_fee' => [
                    'type' => LateFeeType::FIXED->value,
                    'value' => 20,
                ],
            ],
            self::SUBSCRIPTION => [
                'retry' => [
                    'max_retries' => 3,
                    'retry_backoff' => 12,
                ],
            ],
            self::BASIC => [],
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

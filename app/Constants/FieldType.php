<?php

namespace App\Constants;

enum FieldType: string
{
    case TEXT = 'text';
    case NUMBER = 'number';
    case EMAIL = 'email';
    case SELECT = 'select';

    public static function toArray(): array
    {
        return array_column(self::cases(), 'value');
    }

    public static function toSelectArray(): array
    {
        return array_map(fn ($case) => [
            'label' => __("field_types.{$case->value}"),
            'value' => $case->value
        ], self::cases());
    }
}

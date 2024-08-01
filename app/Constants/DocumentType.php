<?php

namespace App\Constants;

enum DocumentType: string
{
    case CC = 'CC';
    case CE = 'CE';
    case NIT = 'NIT';
    case PP = 'PP';

    public static function toSelectArray(): array
    {
        return array_map(fn ($case) => [
            'label' => __("document_types.{$case->value}"),
            'value' => $case->value
        ], self::cases());
    }
}

<?php

namespace App\Concerns;

trait EnumToArray
{
    public static function toArray(): array
    {
        return array_column(self::cases(), 'value');
    }
}

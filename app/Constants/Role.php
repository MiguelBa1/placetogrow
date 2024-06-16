<?php

namespace App\Constants;

enum Role: string
{
    case ADMIN = 'admin';
    case CUSTOMER = 'customer';
    case USER = 'user';

    public static function getRoles(): array
    {
        return [
            self::ADMIN,
            self::CUSTOMER,
            self::USER,
        ];
    }
}

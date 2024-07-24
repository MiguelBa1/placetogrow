<?php

namespace App\Constants;

enum Role: string
{
    case ADMIN = 'admin';
    case CUSTOMER = 'customer';
    case GUEST = 'guest';
    case ROLE_MANAGER = 'role_manager';
}

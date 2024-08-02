<?php

namespace App\Constants;

use App\Concerns\EnumToArray;

enum PaymentStatus: string
{
    use EnumToArray;

    case FAILED = 'ERROR';

    case PENDING = 'PENDING';
    case APPROVED = 'APPROVED';
    case REJECTED = 'REJECTED';
    case UNKNOWN = 'UNKNOWN';

}

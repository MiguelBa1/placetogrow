<?php

namespace App\Constants;

use App\Concerns\EnumToArray;

enum BillingUnit: string
{
    use EnumToArray;

    case DAYS = 'days';
    case MONTHS = 'months';
    case YEARS = 'years';
}

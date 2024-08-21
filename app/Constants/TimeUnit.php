<?php

namespace App\Constants;

use App\Concerns\EnumToArray;

enum TimeUnit: string
{
    use EnumToArray;

    case DAYS = 'days';
    case MONTHS = 'months';
    case YEARS = 'years';
}

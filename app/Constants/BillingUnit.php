<?php

namespace App\Constants;

enum BillingUnit: string
{
    case DAYS = 'days';
    case MONTHS = 'months';
    case YEARS = 'years';
}

<?php

namespace App\Constants;

enum LateFeeType: string
{
    case FIXED = 'fixed';
    case PERCENTAGE = 'percentage';
}

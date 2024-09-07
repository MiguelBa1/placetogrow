<?php

namespace App\Constants;

enum PlaceToPayStatus: string
{
    case PENDING = 'PENDING';
    case APPROVED = 'APPROVED';
    case REJECTED = 'REJECTED';
    case APPROVED_PARTIAL = 'APPROVED_PARTIAL';
    case PARTIAL_EXPIRED = 'PARTIAL_EXPIRED';
}

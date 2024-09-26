<?php

namespace App\Constants;

enum ImportStatus: string
{
    case PENDING = 'PENDING';
    case READY = 'READY';
    case FAILED = 'FAILED';

    case HAS_ERRORS = 'HAS_ERRORS';
}

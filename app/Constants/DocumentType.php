<?php

namespace App\Constants;

enum DocumentType: string
{
    case CC = 'CC';
    case CE = 'CE';
    case NIT = 'NIT';
    case PASSPORT = 'PASSPORT';
}

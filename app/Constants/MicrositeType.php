<?php

namespace App\Constants;

enum MicrositeType: string
{
    case INVOICE = 'invoice';
    case SUBSCRIPTION = 'subscription';
    case BASIC = 'basic';
}

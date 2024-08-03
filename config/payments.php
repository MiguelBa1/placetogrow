<?php

return [
    'check_interval_minutes' => env('CHECK_PAYMENTS_MINUTES', 5),

    'placetopay' => [
        'url' => env('P2P_URL'),
        'login' => env('P2P_LOGIN'),
        'tranKey' => env('P2P_SECRET_KEY'),
        'expiration' => now()->addMinutes(15)->format('c'),
    ],
];

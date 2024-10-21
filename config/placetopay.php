<?php

return [
    'url' => env('P2P_URL'),
    'login' => env('P2P_LOGIN'),
    'tranKey' => env('P2P_SECRET_KEY'),
    'expiration' => now()->addMinutes(15)->format('c'),
];

<?php

return [
    'check_interval_minutes' => env('CHECK_SUBSCRIPTIONS_MINUTES', 5),
    'notification' => [
        'days_before_charge' => env('DAYS_BEFORE_SUBSCRIPTION_CHARGE', 3),
    ],
];

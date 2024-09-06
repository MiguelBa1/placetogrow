<?php

return [
    'custom' => [
        'validation_rules' => [
            'invalid' => 'The field contains invalid validation rules.',
        ],
        'subscription' => [
            'billing_frequency' => 'The billing frequency must be a divisor of the total duration.',
        ],
    ],
    'attributes' => [
        'name' => 'name',
        'description' => 'description',
    ],
];

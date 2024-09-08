<?php

use App\Constants\SubscriptionStatus;

return [
    'plans' => [
        'basic' => [
            'name' => 'Basic Plan',
            'description' => "Access to basic features.\n\n### Benefits\n\n- ✅ Customer support via email.\n- 📦 Free shipping on orders over $ 30.\n- 🛒 Exclusive access to offers and discounts.\n- ❌ Notifications about new products and collections.\n- ❌ Free returns.",
        ],
        'medium' => [
            'name' => 'Medium Plan',
            'description' => "Access to advanced features.\n\n### Benefits\n\n- ✅ Customer support via email and chat.\n- 📦 Free shipping on orders over $ 30.\n- 🛒 Exclusive access to offers and discounts.\n- 🔔 Notifications about new products and collections.\n- ❌ Free returns.",
        ],
        'premium' => [
            'name' => 'Premium Plan',
            'description' => "Access to premium features, including priority support.\n\n### Benefits\n\n- ✅ 24/7 customer support via email and chat.\n- 📦 Free shipping on orders over $ 30.\n- 🛒 Exclusive access to offers and discounts.\n- 🔔 Notifications about new products and collections.\n- ✅ Free returns.",
        ],
    ],

    'statuses' => [
        SubscriptionStatus::ACTIVE->value => 'Active',
        SubscriptionStatus::INACTIVE->value => 'Inactive',
        SubscriptionStatus::CANCELED->value => 'Canceled',
        SubscriptionStatus::PENDING->value => 'Pending',
    ],
];

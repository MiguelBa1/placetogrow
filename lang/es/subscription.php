<?php

use App\Constants\SubscriptionStatus;

return [
    'plans' => [
        'basic' => [
            'name' => 'Plan Básico',
            'description' => "Acceso a funciones básicas.\n\n### Beneficios\n\n- ✅ Atención al cliente por correo electrónico.\n- 📦 Envío gratis en pedidos superiores a $ 30.\n- 🛒 Acceso exclusivo a ofertas y descuentos.\n- ❌ Notificaciones sobre nuevos productos y colecciones.\n- ❌ Devoluciones gratuitas.",
        ],
        'medium' => [
            'name' => 'Plan Medium',
            'description' => "Acceso a funciones avanzadas.\n\n### Beneficios\n\n- ✅ Atención al cliente por correo electrónico y chat.\n- 📦 Envío gratis en pedidos superiores a $ 30.\n- 🛒 Acceso exclusivo a ofertas y descuentos.\n- 🔔 Notificaciones sobre nuevos productos y colecciones.\n- ❌ Devoluciones gratuitas.",
        ],
        'premium' => [
            'name' => 'Plan Premium',
            'description' => "Acceso a funciones premium, incluyendo soporte prioritario.\n\n### Beneficios\n\n- ✅ Atención al cliente por correo electrónico y chat 24/7.\n- 📦 Envío gratis en pedidos superiores a $ 30.\n- 🛒 Acceso exclusivo a ofertas y descuentos.\n- 🔔 Notificaciones sobre nuevos productos y colecciones.\n- ✅ Devoluciones gratuitas.",
        ],
    ],
    'statuses' => [
        SubscriptionStatus::ACTIVE->value => 'Activo',
        SubscriptionStatus::INACTIVE->value => 'Inactivo',
        SubscriptionStatus::CANCELED->value => 'Cancelado',
        SubscriptionStatus::PENDING->value => 'Pendiente',
    ],
    'mail' => [
        'subject' => 'Enlace de acceso a suscripciones',
        'greeting' => 'Accede a tus suscripciones',
        'message' => 'Haz clic en el botón de abajo para ver tus suscripciones activas.',
        'button_text' => 'Ver Suscripciones',
        'alt_message' => 'Si tienes problemas para hacer clic en el botón "Ver Suscripciones", copia y pega la siguiente URL en tu navegador web:',
        'thanks' => 'Gracias,',
    ],
];

<?php

use App\Constants\SubscriptionStatus;

return [
    'plans' => [
        'basic' => [
            'name' => 'Plan Básico',
            'description' => "Acceso a funciones básicas.\n\n### Beneficios\n\n- ✅ Atención al cliente por correo electrónico.\n- 📦 Envío gratis en pedidos superiores a $ 30.\n- 🛒 Acceso exclusivo a ofertas y descuentos.\n- ❌ Notificaciones sobre nuevos productos y colecciones.\n- ❌ Devoluciones gratuitas.",
        ],
        'medium' => [
            'name' => 'Plan Medio',
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

    'access_link_mail' => [
        'subject' => 'Enlace de acceso a suscripciones',
        'greeting' => 'Accede a tus suscripciones',
        'message' => 'Haz clic en el botón de abajo para ver tus suscripciones activas.',
        'button_text' => 'Ver Suscripciones',
        'alt_message' => 'Si tienes problemas para hacer clic en el botón "Ver Suscripciones", copia y pega la siguiente URL en tu navegador web:',
        'thanks' => 'Gracias,',
    ],

    'upcoming_charge_mail' => [
        'subject' => 'Próximo Cobro de Suscripción',
        'title' => 'Tu Pago de Suscripción se Acerca',
        'greeting' => 'Hola :name,',
        'body' => 'Queremos informarte que tu pago de suscripción de :currency :amount será cobrado el :date.',
        'microsite' => 'Micrositio',
        'button' => 'Gestionar tu Suscripción',
        'thank_you' => 'Gracias por tu continuo apoyo',
    ],

    'subscription_created_mail' => [
        'subject' => 'Confirmación de Suscripción',
        'greeting' => 'Hola :name,',
        'message' => 'Gracias por suscribirte al plan :plan en el micrositio :microsite. Esperamos que disfrutes de nuestros servicios.',
        'farewell' => 'Saludos cordiales,',
        'team' => 'El equipo de :microsite',
    ],

    'expiration_reminder' => [
        'subject' => 'Recordatorio de Vencimiento de Suscripción',
        'greeting' => 'Hola :name,',
        'body' => 'Tu suscripción al plan :plan en el micrositio :microsite vencerá el :end_date.',
        'renew_cta' => 'Puedes renovarla en nuestra página antes de que expire para seguir disfrutando de nuestros servicios.',
        'button_text' => 'Renovar suscripción',
        'thank_you' => 'Gracias por tu preferencia,',
        'signature' => 'El equipo de :microsite',
    ],
];

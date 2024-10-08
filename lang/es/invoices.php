<?php

use App\Constants\InvoiceStatus;

return [
    "invalid_microsite_type" => "Tipo de micrositio inválido",
    "statuses" => [
        InvoiceStatus::PENDING->value => "Pendiente",
        InvoiceStatus::PAID->value => "Pagado",
        InvoiceStatus::EXPIRED->value => "Expirado",
    ],
    "import" => [
        "success" => "La importación ha comenzado. Se le notificará por correo electrónico una vez que esté completa.",
        "mail" => [
            "subject" => "Resultados de la Importación de Facturas",
            "title" => "Resultados de la Importación de Facturas",
            "success" => "La importación de facturas se ha completado con éxito.",
            "failures_title" => "Fallos:",
            "no_failures" => "No hubo fallos durante la importación.",
            "failure" => "Fila :row: :errors",
        ],
    ],
    "due_soon_mail" => [
        "subject" => "Tu factura :reference está próxima a vencer",
        "title" => "Factura Próxima a Vencer",
        "greeting" => "Hola :name,",
        "body" => "Te informamos que tu factura de :currency :amount con referencia :reference en el micrositio :microsite vencerá el :date.",
        "button" => "Pagar Factura",
        "thank_you" => "Gracias por tu atención",
    ],
];

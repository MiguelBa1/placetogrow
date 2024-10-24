<?php

use App\Constants\InvoiceStatus;

return [
    "invalid_microsite_type" => "Invalid microsite type",
    "statuses" => [
        InvoiceStatus::PENDING->value => "Pending",
        InvoiceStatus::PAID->value => "Paid",
        InvoiceStatus::EXPIRED->value => "Expired",
    ],
    "import" => [
        "success" => "The import has started. You will be notified by email once it is complete.",
        "mail" => [
            "subject" => "Invoice Import Results",
            "title" => "Invoice Import Results",
            "success" => "The invoice import has been completed successfully.",
            "failures_title" => "Failures:",
            "no_failures" => "There were no failures during the import.",
            "failure" => "Row :row: :errors",
        ],
    ],
    'due_soon_mail' => [
        'subject' => 'Tu factura :reference está próxima a vencer',
        'title' => 'Factura Próxima a Vencer',
        'greeting' => 'Hola :name,',
        'body' => 'Te informamos que tu factura de :currency :amount con referencia :reference en el micrositio :microsite vencerá el :date.',
        'button' => 'Pagar Factura',
        'thank_you' => 'Gracias por tu atención',
    ],
];

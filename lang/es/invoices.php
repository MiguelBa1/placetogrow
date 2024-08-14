<?php

use App\Constants\InvoiceStatus;

return [
    "invalid_microsite_type" => "Tipo de micrositio inválido",
    "statuses" => [
        InvoiceStatus::PENDING->value => "Pendiente",
        InvoiceStatus::PAID->value => "Pagado",
        InvoiceStatus::EXPIRED->value => "Expirado",
    ],
];

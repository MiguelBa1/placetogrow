<?php
use App\Constants\PaymentStatus;

return [
    "invoice_not_found" => "Factura no encontrada",
    "statuses" => [
        PaymentStatus::FAILED->value => "Fallido",
        PaymentStatus::OK->value => "OK",
        PaymentStatus::PENDING->value => "Pendiente",
        PaymentStatus::APPROVED->value => "Aprobado",
        PaymentStatus::REJECTED->value => "Rechazado",
        PaymentStatus::UNKNOWN->value => "Desconocido",
    ],
];

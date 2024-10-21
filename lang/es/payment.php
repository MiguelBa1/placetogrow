<?php
use App\Constants\PaymentStatus;

return [
    "invoice_not_found" => "Factura no encontrada",
    "invoice_invalid_status" => "La factura no se puede pagar",
    "statuses" => [
        PaymentStatus::FAILED->value => "Fallido",
        PaymentStatus::PENDING->value => "Pendiente",
        PaymentStatus::APPROVED->value => "Aprobado",
        PaymentStatus::REJECTED->value => "Rechazado",
        PaymentStatus::UNKNOWN->value => "Desconocido",
    ],
    "create_failed" => "No se pudo crear el pago",
    "check_failed" => "No se pudo verificar el pago",
];

<?php

use App\Constants\PaymentStatus;

return [
    "invoice_not_found" => "Invoice not found",
    "invoice_invalid_status" => "Invoice cannot be paid",
    "statuses" => [
        PaymentStatus::FAILED->value => "Failed",
        PaymentStatus::PENDING->value => "Pending",
        PaymentStatus::APPROVED->value => "Approved",
        PaymentStatus::REJECTED->value => "Rejected",
        PaymentStatus::UNKNOWN->value => "Unknown",
    ],
    "create_failed" => "Payment could not be created",
    "check_failed" => "Payment could not be verified",
];

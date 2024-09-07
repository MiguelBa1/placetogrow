<?php

use App\Constants\PaymentStatus;

return [
    "invoice_not_found" => "Invoice not found",
    "statuses" => [
        PaymentStatus::FAILED->value => "Failed",
        PaymentStatus::PENDING->value => "Pending",
        PaymentStatus::APPROVED->value => "Approved",
        PaymentStatus::REJECTED->value => "Rejected",
        PaymentStatus::UNKNOWN->value => "Unknown",
    ],
];

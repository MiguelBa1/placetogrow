<?php

namespace App\Actions\Payment;

use App\Models\Customer;
use App\Models\Microsite;
use App\Models\Payment;
use Illuminate\Support\Str;

class CreatePaymentAction
{

    public function execute(Customer $customer, Microsite $microsite, array $paymentData): Payment
    {
        $reference = strtoupper($microsite->type->value . '_' . Str::random(10));

        $description = $paymentData['payment_description'] ?? 'Payment generated for the microsite ' . $microsite->name;

        return Payment::create([
            'customer_id' => $customer->id,
            'microsite_id' => $microsite->id,
            'plan_id' => $paymentData['plan_id'] ?? null,
            'invoice_id' => $paymentData['invoice_id'] ?? null,
            'description' => $description,
            'reference' => $reference,
            'currency' => $microsite->payment_currency->value,
            'amount' => $paymentData['amount'],
            'additional_data' => $paymentData['additional_data'] ?? null,
        ]);
    }
}

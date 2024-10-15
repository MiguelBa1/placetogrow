<?php

namespace App\Actions\InvoicePayment;

use App\Constants\InvoiceStatus;
use App\Models\Invoice;
use App\Models\Microsite;
use Illuminate\Validation\ValidationException;

class PrepareInvoicePaymentAction
{

    /**
     * @throws ValidationException
     */
    public function execute(Microsite $microsite, Invoice $invoice, array $additionalFieldsData = []): array
    {
        $this->validateInvoice($invoice);

        $additionalData = [];
        foreach ($microsite->fields as $field) {
            if ($field->modifiable) {
                $additionalData[$field->name] = $additionalFieldsData[$field->name] ?? null;
            }
        }

        return [
            'invoice_id' => $invoice->id,
            'name' => $invoice->name,
            'last_name' => $invoice->last_name,
            'email' => $invoice->email,
            'document_number' => $invoice->document_number,
            'document_type' => $invoice->document_type,
            'phone' => $invoice->phone,
            'amount' => $invoice->total_amount ?? $invoice->amount,
            'payment_description' => 'Invoice payment ' . $invoice->reference,
            'additional_data' => $additionalData,
        ];
    }

    /**
     * @throws ValidationException
     */
    private function validateInvoice(Invoice $invoice): void
    {
        if ($invoice->status !== InvoiceStatus::PENDING) {
            throw ValidationException::withMessages(['payment' => __('payment.invoice_invalid_status')]);
        }
    }
}

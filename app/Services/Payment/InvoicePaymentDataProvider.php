<?php

namespace App\Services\Payment;

use App\Constants\InvoiceStatus;
use App\Contracts\PaymentDataProviderInterface;
use App\Models\Invoice;
use Illuminate\Validation\ValidationException;

class InvoicePaymentDataProvider implements PaymentDataProviderInterface
{
    /**
     * @throws ValidationException
     */
    public function getPaymentData(array $data): array
    {
        $microsite = request()->route('microsite');

        /** @var Invoice|null $invoice */
        $invoice = Invoice::query()->where('reference', $data['reference'])
            ->where('document_number', $data['document_number'])
            ->where('microsite_id', $microsite->id)
            ->first();

        $this->validateInvoice($invoice);

        return [
            'name' => $invoice->name,
            'last_name' => $invoice->last_name,
            'email' => $invoice->email,
            'document_number' => $invoice->document_number,
            'document_type' => $invoice->document_type,
            'phone' => $invoice->phone,
            'amount' => $invoice->amount,
            'payment_description' => 'Invoice payment ' . $invoice->reference,
        ];
    }

    /**
     * @throws ValidationException
     */
    private function validateInvoice(?Invoice $invoice): void
    {
        if (is_null($invoice)) {
            throw ValidationException::withMessages(['payment' => __('payment.invoice_not_found')]);
        }

        if ($invoice->status === InvoiceStatus::PAID) {
            throw ValidationException::withMessages(['payment' => __('payment.invoice_already_paid')]);
        }

        if ($invoice->status === InvoiceStatus::EXPIRED) {
            throw ValidationException::withMessages(['payment' => __('payment.invoice_expired')]);
        }
    }
}

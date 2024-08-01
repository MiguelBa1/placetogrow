<?php

namespace App\Services\Payment;

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

        $invoice = Invoice::where('reference', $data['reference'])
            ->where('document_number', $data['document_number'])
            ->where('microsite_id', $microsite->id)
            ->first();

        if (is_null($invoice)) {
            throw ValidationException::withMessages(['payment' => __('payment.invoice_not_found')]);
        }

        return [
            'name' => $invoice->name,
            'last_name' => $invoice->last_name,
            'email' => $invoice->email,
            'document_number' => $invoice->document_number,
            'document_type' => $invoice->document_type,
            'phone' => $invoice->phone,
            'amount' => $invoice->amount,
            'payment_description' => 'Invoice payment' . $invoice->reference,
        ];
    }
}

<?php

namespace App\Http\Resources\InvoicePayment;

use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Invoice
 */
class PendingInvoiceListResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'reference' => $this->reference,
            'status' => [
                'label' => __("invoices.statuses.{$this->status->value}"),
                'value' => $this->status->value,
            ],
            'name' => $this->name . ' ' . $this->last_name,
            'amount' => $this->amount,
            'currency' => $this->microsite->payment_currency,
            'expiration_date' => $this->expiration_date->format('d/m/Y'),
        ];
    }
}

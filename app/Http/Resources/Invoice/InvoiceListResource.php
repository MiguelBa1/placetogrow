<?php

namespace App\Http\Resources\Invoice;

use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Invoice
 */
class InvoiceListResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'reference' => $this->reference,
            'document_number' => $this->document_number,
            'status' => [
                'label' => __("invoices.statuses.{$this->status->value}"),
                'value' => $this->status->value,
            ],
            'name' => $this->name,
            'amount' => "$ " . number_format($this->amount, 2),
            'expiration_date' => $this->expiration_date->format('d/m/Y'),
        ];
    }
}

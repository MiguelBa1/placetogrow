<?php

namespace App\Http\Resources\CustomerInvoice;

use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Invoice
 */
class CustomerInvoiceResource extends JsonResource
{

    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'reference' => $this->reference,
            'document_type' => $this->document_type,
            'document_number' => $this->document_number,
            'name' => "{$this->name} {$this->last_name}",
            'email' => $this->email,
            'phone' => $this->phone,
            'amount' => number_format($this->amount, 2),
            'expiration_date' => date('Y-m-d', strtotime($this->expiration_date)),
            'status' => [
                'label' => __("invoices.statuses.{$this->status->value}"),
                'value' => $this->status->value,
            ],
            'microsite' => $this->whenLoaded('microsite', function () {
                return [
                    'name' => $this->microsite->name,
                    'slug' => $this->microsite->slug,
                ];
            }),
        ];
    }
}

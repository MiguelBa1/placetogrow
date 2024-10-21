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
            'document_number' => $this->document_number,
            'name' => "{$this->name} {$this->last_name}",
            'amount' => "$ " . number_format($this->amount, 2) . " " . $this->microsite->payment_currency->value,
            'expiration_date' => $this->expiration_date->format('d/m/Y'),
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

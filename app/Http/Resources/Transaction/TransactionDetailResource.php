<?php

namespace App\Http\Resources\Transaction;

use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Payment
 */
class TransactionDetailResource extends JsonResource
{

    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'reference' => $this->reference,
            'description' => $this->description,
            'status' => [
                'value' => $this->status->value,
                'label' => __("payment.statuses.{$this->status->value}")
            ],
            'amount' => "$ " . number_format($this->amount, 2),
            'payment_date' => $this->payment_date,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'currency' => __('currency_types.' . $this->currency),
            'additional_data' => $this->additional_data ?: null,
            'microsite' => $this->microsite->name,
            'customer' => $this->customer->only('id', 'name', 'last_name', 'email', 'document_number', 'document_type'),
        ];
    }
}

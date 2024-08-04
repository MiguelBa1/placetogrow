<?php

namespace App\Http\Resources\Transaction;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

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
            'amount' => $this->amount,
            'payment_date' => $this->payment_date,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'currency' => __('currency_types.' . $this->currency),
            'microsite' => $this->microsite->name,
            'customer' => $this->customer->only('id', 'name', 'last_name', 'email', 'document_number', 'document_type'),
        ];
    }
}

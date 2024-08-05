<?php

namespace App\Http\Resources\Transaction;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TransactionListResource extends JsonResource
{

    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'reference' => $this->reference,
            'microsite' => $this->microsite->name,
            'status' => [
                'value' => $this->status->value,
                'label' => __("payment.statuses.{$this->status->value}")
            ],
            'amount' => $this->amount,
            'payment_date' => $this->payment_date,
        ];
    }
}

<?php

namespace App\Http\Resources\Transaction;

use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Payment
 */
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
            'amount' => "$ " . number_format($this->amount, 2),
            'payment_date' => $this->payment_date->format('d/m/Y'),
        ];
    }
}

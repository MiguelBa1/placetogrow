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
        $totalAmount = $this->total_amount ?? $this->amount;
        $lateFee = $this->late_fee ?? 0;

        return [
            'id' => $this->id,
            'reference' => $this->reference,
            'status' =>__("invoices.statuses.{$this->status->value}"),
            'name' => $this->name . ' ' . $this->last_name,
            'amount' => '$' . number_format($this->amount, 2),
            'late_fee' => '$' . number_format($lateFee, 2),
            'total_amount' => '$' . number_format($totalAmount, 2),
            'currency' => $this->microsite->payment_currency,
            'expiration_date' => $this->expiration_date->format('d/m/Y'),
        ];
    }
}

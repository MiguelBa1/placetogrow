<?php

namespace App\Actions\Invoice;

use App\Models\Invoice;
use App\Models\Microsite;
use Illuminate\Support\Carbon;

class StoreInvoiceAction
{
    public function execute(Microsite $microsite, array $data): Invoice
    {
        $expirationDays = $microsite->payment_expiration;

        return Invoice::create([
            'microsite_id' => $microsite->id,
            'reference' => $data['reference'],
            'document_type' => $data['document_type'],
            'document_number' => $data['document_number'],
            'name' => $data['name'],
            'last_name' => $data['last_name'],
            'email' => $data['email'],
            'phone' => $data['phone'],
            'amount' => $data['amount'],
            'expiration_date' => Carbon::now()->addDays($expirationDays),
        ]);
    }
}

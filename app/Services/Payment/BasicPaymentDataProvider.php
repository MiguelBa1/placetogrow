<?php

namespace App\Services\Payment;

use App\Contracts\PaymentDataProviderInterface;
use Illuminate\Database\Eloquent\Collection;

class BasicPaymentDataProvider implements PaymentDataProviderInterface
{
    public function getPaymentData(array $data, Collection $fields): array
    {
        $additionalData = [];

        foreach ($fields as $field) {
            if ($field->modifiable) {
                $additionalData[$field->name] = $data[$field->name] ?? null;
            }
        }

        return [
            'name' => $data['name'],
            'last_name' => $data['last_name'],
            'email' => $data['email'],
            'document_number' => $data['document_number'],
            'document_type' => $data['document_type'],
            'phone' => $data['phone'],
            'amount' => $data['amount'],
            'payment_description' => $data['payment_description'],
            'additional_data' => $additionalData,
        ];
    }
}

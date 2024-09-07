<?php

namespace App\Actions\Customer;

use App\Models\Customer;

class StoreCustomerAction
{
    public function execute(array $customerData): Customer
    {
        /** @var Customer $customerData */
        $customerData = Customer::query()->firstOrCreate(
            ['document_number' => $customerData['document_number']],
            [
                'name' => $customerData['name'],
                'last_name' => $customerData['last_name'],
                'document_type' => $customerData['document_type'],
                'document_number' => $customerData['document_number'],
                'phone' => $customerData['phone'],
                'email' => $customerData['email'],
            ]
        );

        return $customerData;
    }
}

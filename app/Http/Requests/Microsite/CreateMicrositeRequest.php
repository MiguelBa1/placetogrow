<?php

namespace App\Http\Requests\Microsite;

class CreateMicrositeRequest extends BaseMicrositeRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rules = $this->commonRules(null);

        return array_merge($rules, [
            'name' => array_merge(['required'], $rules['name']),
            'logo' => array_merge(['required'], $rules['logo']),
            'category_id' => array_merge(['required'], $rules['category_id']),
            'payment_currency' => array_merge(['required'], $rules['payment_currency']),
            'payment_expiration' => array_merge(['required'], $rules['payment_expiration']),
            'type' => array_merge(['required'], $rules['type']),
            'responsible_name' => array_merge(['required'], $rules['responsible_name']),
            'responsible_document_number' => array_merge(['required'], $rules['responsible_document_number']),
            'responsible_document_type' => array_merge(['required'], $rules['responsible_document_type']),
        ]);
    }
}

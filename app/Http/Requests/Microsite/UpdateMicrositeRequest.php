<?php

namespace App\Http\Requests\Microsite;

class UpdateMicrositeRequest extends BaseMicrositeRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $micrositeId = $this->route('microsite')->id ?? null;

        $rules = $this->commonRules($micrositeId);

        return array_merge($rules, [
            'name' => array_merge(['sometimes'], $rules['name']),
            'logo' => array_merge(['nullable'], $rules['logo']),
            'category_id' => array_merge(['sometimes'], $rules['category_id']),
            'payment_currency' => array_merge(['sometimes'], $rules['payment_currency']),
            'payment_expiration' => array_merge(['sometimes'], $rules['payment_expiration']),
            'type' => array_merge(['sometimes'], $rules['type']),
            'responsible_name' => array_merge(['sometimes'], $rules['responsible_name']),
            'responsible_document_number' => array_merge(['sometimes'], $rules['responsible_document_number']),
            'responsible_document_type' => array_merge(['sometimes'], $rules['responsible_document_type']),
        ]);
    }
}

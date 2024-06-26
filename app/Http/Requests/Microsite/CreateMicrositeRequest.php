<?php

namespace App\Http\Requests\Microsite;

use Illuminate\Validation\Rule;

class CreateMicrositeRequest extends BaseMicrositeRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rules = [
            'name' => ['required', Rule::unique('microsites')],
            'logo' => ['required'],
            'category_id' => ['required'],
            'payment_currency' => ['required'],
            'payment_expiration' => ['required'],
            'type' => ['required'],
            'responsible_name' => ['required'],
            'responsible_document_number' => ['required'],
            'responsible_document_type' => ['required'],
        ];

        return array_merge_recursive($rules, $this->commonRules());
    }
}

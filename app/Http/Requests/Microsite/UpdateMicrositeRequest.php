<?php

namespace App\Http\Requests\Microsite;

use App\Constants\CurrencyType;
use App\Constants\DocumentType;
use App\Constants\MicrositeType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateMicrositeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $micrositeId = $this->route('microsite')->id ?? null;

        return [
            'name' => [
                'sometimes',
                'string',
                'max:150',
                Rule::unique('microsites')->ignore($micrositeId),
            ],
            'logo' => ['nullable', 'image', 'max:2048', 'mimes:jpeg,png,jpg'],
            'category_id' => ['sometimes', 'exists:categories,id'],
            'payment_currency' => ['sometimes', Rule::in(array_column(CurrencyType::cases(), 'value'))],
            'payment_expiration' => ['sometimes', 'date'],
            'type' => ['sometimes', Rule::in(array_column(MicrositeType::cases(), 'value'))],
            'responsible_name' => ['sometimes', 'string', 'max:100'],
            'responsible_document_number' => ['sometimes', 'string', 'max:20'],
            'responsible_document_type' => ['sometimes', Rule::in(array_column(DocumentType::cases(), 'value'))],
        ];
    }
}

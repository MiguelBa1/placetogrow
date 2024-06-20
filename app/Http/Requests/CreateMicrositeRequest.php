<?php

namespace App\Http\Requests;

use App\Constants\CurrencyType;
use App\Constants\DocumentType;
use App\Constants\MicrositeType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateMicrositeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:100'],
            'logo' => ['nullable', 'string', 'max:255'],
            'category_id' => ['required', 'exists:categories,id'],
            'payment_currency' => ['required', Rule::in(array_column(CurrencyType::cases(), 'value'))],
            'payment_expiration' => ['required', 'integer', 'min:1', 'max:365'],
            'type' => ['required', Rule::in(array_column(MicrositeType::cases(), 'value'))],
            'slug' => [
                'required',
                'string',
                'max:150',
                Rule::unique('microsites')->ignore($this->microsite),
            ],
            'responsible_name' => ['required', 'string', 'max:100'],
            'responsible_document_number' => ['required', 'string', 'max:20'],
            'responsible_document_type' => ['required', Rule::in(array_column(DocumentType::cases(), 'value'))],
        ];
    }
}

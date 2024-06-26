<?php

namespace App\Http\Requests;

use App\Constants\CurrencyType;
use App\Constants\DocumentType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreatePaymentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, array>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'regex:/^[a-zA-Z ]+$/'],
            'last_name' => ['required', 'string', 'regex:/^[a-zA-Z ]+$/'],
            'email' => ['required', 'email'],
            'document_type' => ['required', Rule::in(array_column(DocumentType::cases(), 'value'))],
            'document_number' => ['required', 'string'],
            'phone' => ['required', 'string'],
            'currency' => ['required', Rule::in(array_column(CurrencyType::cases(), 'value'))],
            'amount' => ['required', 'numeric'],
        ];
    }
}

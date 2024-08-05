<?php

namespace App\Http\Requests\Transaction;

use App\Constants\PaymentStatus;
use Illuminate\Foundation\Http\FormRequest;

class FilterTransactionsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'microsite' => ['nullable', 'string', 'max:100'],
            'status' => [
                'nullable',
                'string',
                'in:' . implode(',', PaymentStatus::toArray()),
            ],
            'reference' => ['nullable', 'string', 'max:100'],
            'document' => ['nullable', 'string', 'max:100'],
        ];
    }
}

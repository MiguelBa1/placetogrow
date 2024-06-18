<?php

namespace App\Http\Requests;

use App\Constants\CurrencyType;
use App\Constants\MicrositeType;
use Illuminate\Foundation\Http\FormRequest;

class CreateMicrositeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'logo' => 'nullable|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'payment_currency' => 'required|in:' . implode(',', array_column(CurrencyType::cases(), 'value')),
            'payment_expiration' => 'required|integer|min:1',
            'type' => 'required|in:' . implode(',', array_column(MicrositeType::cases(), 'value')),
        ];
    }
}

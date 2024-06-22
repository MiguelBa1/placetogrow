<?php

namespace App\Http\Requests\Microsite;

use App\Constants\CurrencyType;
use App\Constants\DocumentType;
use App\Constants\MicrositeType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

abstract class BaseMicrositeRequest extends FormRequest
{
    protected function commonRules($micrositeId): array
    {
        return [
            'name' => [
                'string',
                'max:150',
                Rule::unique('microsites')->ignore($micrositeId),
            ],
            'logo' => ['image', 'max:2048', 'mimes:jpeg,png,jpg'],
            'category_id' => ['exists:categories,id'],
            'payment_currency' => [Rule::in(array_column(CurrencyType::cases(), 'value'))],
            'payment_expiration' => ['date'],
            'type' => [Rule::in(array_column(MicrositeType::cases(), 'value'))],
            'responsible_name' => ['string', 'max:100'],
            'responsible_document_number' => ['string', 'max:20'],
            'responsible_document_type' => [Rule::in(array_column(DocumentType::cases(), 'value'))],
        ];
    }
}

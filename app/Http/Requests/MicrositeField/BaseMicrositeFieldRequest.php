<?php

namespace App\Http\Requests\MicrositeField;

use App\Constants\FieldType;
use Illuminate\Foundation\Http\FormRequest;

abstract class BaseMicrositeFieldRequest extends FormRequest
{
    protected function commonRules(): array
    {
        return [
            'name' => ['string', 'max:100'],
            'type' => ['string', 'in:' . implode(',', FieldType::toArray())],
            'validation_rules' => ['nullable', 'string'],
            'translation_es' => ['string', 'max:100'],
            'translation_en' => ['string', 'max:100'],
            'options' => ['nullable', 'array'],
            'options.*' => ['required_with:options', 'string'],
        ];
    }
}

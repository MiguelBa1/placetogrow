<?php

namespace App\Http\Requests\MicrositeField;

use App\Constants\FieldType;
use Illuminate\Foundation\Http\FormRequest;

class MicrositeFieldRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:100', 'unique:microsite_fields,name'],
            'type' => ['required', 'string', 'in:' . implode(',', FieldType::toArray())],
            'validation_rules' => ['nullable', 'string'],
            'translation_es' => ['required', 'string', 'max:100'],
            'translation_en' => ['required', 'string', 'max:100'],
            'options' => ['nullable', 'array'],
            'options.*' => ['required_with:options', 'string'],
        ];
    }
}

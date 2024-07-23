<?php

namespace App\Http\Requests\MicrositeField;

use Illuminate\Foundation\Http\FormRequest;

class MicrositeFieldRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:100'],
            'type' => ['required', 'string', 'max:50'],
            'validation_rules' => ['nullable', 'string'],
            'translation_es' => ['required', 'string', 'max:100'],
            'translation_en' => ['required', 'string', 'max:100'],
        ];
    }
}

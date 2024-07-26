<?php

namespace App\Http\Requests\MicrositeField;

class CreateMicrositeFieldRequest extends BaseMicrositeFieldRequest
{
    public function rules(): array
    {
        $rules = [
            'name' => ['required', 'unique:microsite_fields,name'],
            'type' => ['required'],
            'translation_es' => ['required'],
            'translation_en' => ['required'],
        ];

        return array_merge_recursive($rules, $this->commonRules());
    }
}

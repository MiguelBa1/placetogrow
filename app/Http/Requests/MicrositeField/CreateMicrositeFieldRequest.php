<?php

namespace App\Http\Requests\MicrositeField;

use Illuminate\Validation\Rule;

class CreateMicrositeFieldRequest extends BaseMicrositeFieldRequest
{
    public function rules(): array
    {
        $rules = [
            'name' => [
                'required',
                Rule::unique('microsite_fields')
                    ->where(
                        fn ($query) => $query
                            ->where('microsite_id', $this->microsite->id)
                            ->where('name', $this->input('name'))
                    )
            ],
            'type' => ['required'],
            'translation_es' => ['required'],
            'translation_en' => ['required'],
        ];

        return array_merge_recursive($rules, $this->commonRules());
    }
}

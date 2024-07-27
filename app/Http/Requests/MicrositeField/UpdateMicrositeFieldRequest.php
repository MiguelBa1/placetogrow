<?php

namespace App\Http\Requests\MicrositeField;

use Illuminate\Validation\Rule;

class UpdateMicrositeFieldRequest extends BaseMicrositeFieldRequest
{

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $micrositeFieldId = $this->route('field')->id ?? null;

        $rules = [
            'name' => [Rule::unique('microsite_fields')->ignore($micrositeFieldId)],
        ];

        return array_merge_recursive($rules, $this->commonRules());
    }
}

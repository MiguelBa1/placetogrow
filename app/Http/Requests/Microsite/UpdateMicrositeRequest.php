<?php

namespace App\Http\Requests\Microsite;

use Illuminate\Validation\Rule;

class UpdateMicrositeRequest extends BaseMicrositeRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $micrositeId = $this->route('microsite')->id ?? null;

        $rules = [
            'name' => [Rule::unique('microsites')->ignore($micrositeId)],
            'logo' => ['nullable'],
        ];

        return array_merge_recursive($rules, $this->commonRules());
    }
}

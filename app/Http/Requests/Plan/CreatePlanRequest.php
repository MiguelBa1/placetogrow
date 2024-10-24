<?php

namespace App\Http\Requests\Plan;

class CreatePlanRequest extends BasePlanRequest
{
    public function rules(): array
    {
        $rules = [
            'price' => ['required'],
            'total_duration' => ['required'],
            'billing_frequency' => ['required'],
            'time_unit' => ['required'],
            'translations' => ['required'],
            'translations.*.locale' => ['required'],
            'translations.*.name' => ['required'],
            'translations.*.description' => ['required'],
        ];

        return array_merge_recursive($rules, $this->commonRules());
    }
}

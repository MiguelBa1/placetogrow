<?php

namespace App\Http\Requests\Plan;

class UpdatePlanRequest extends BasePlanRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return $this->commonRules();
    }
}

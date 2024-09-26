<?php

namespace App\Http\Requests\Subscription;

class UpdateSubscriptionRequest extends BaseSubscriptionRequest
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

<?php

namespace App\Http\Requests\Subscription;

use App\Constants\BillingUnit;
use Illuminate\Foundation\Http\FormRequest;

abstract class BaseSubscriptionRequest extends FormRequest
{
    protected function commonRules(): array
    {
        return [
            'price' => ['numeric'],
            'total_duration' => ['integer'],
            'billing_frequency' => ['integer'],
            'billing_unit' => ['in:' . implode(',', BillingUnit::toArray())],
            'translations' => ['array'],
            'translations.*.locale' => ['string', 'max:2'],
            'translations.*.name' => ['string', 'max:100'],
            'translations.*.description' => ['string'],
        ];
    }
}

<?php

namespace App\Http\Requests\Subscription;

use App\Constants\TimeUnit;
use Illuminate\Foundation\Http\FormRequest;

abstract class BaseSubscriptionRequest extends FormRequest
{
    protected function commonRules(): array
    {
        return [
            'price' => ['numeric'],
            'total_duration' => ['integer'],
            'billing_frequency' => ['integer'],
            'time_unit' => ['in:' . implode(',', TimeUnit::toArray())],
            'translations' => ['array'],
            'translations.*.locale' => ['string', 'max:2'],
            'translations.*.name' => ['string', 'max:100'],
            'translations.*.description' => ['string'],
        ];
    }

    public function attributes(): array
    {
        return [
            'translations.*.name' => __('validation.attributes.name'),
            'translations.*.description' => __('validation.attributes.description'),
        ];
    }
}

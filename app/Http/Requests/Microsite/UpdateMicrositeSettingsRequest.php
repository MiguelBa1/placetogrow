<?php

namespace App\Http\Requests\Microsite;

use App\Constants\LateFeeType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateMicrositeSettingsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'settings.late_fee.type' => ['nullable', 'string', Rule::in(array_column(LateFeeType::cases(), 'value'))],
            'settings.late_fee.value' => ['nullable', 'numeric', 'min:0'],
            'settings.retry.max_retries' => ['nullable', 'integer', 'min:1'],
            'settings.retry.retry_backoff' => ['nullable', 'integer', 'min:1'],
        ];
    }

    public function attributes(): array
    {
        return [
            'settings.late_fee.type' => __('microsite_settings.last_fee.type'),
            'settings.late_fee.value' => __('microsite_settings.last_fee.value'),
            'settings.retry.max_retries' => __('microsite_settings.retry.max_retries'),
            'settings.retry.retry_backoff' => __('microsite_settings.retry.retry_backoff'),
        ];
    }
}

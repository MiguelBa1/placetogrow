<?php

namespace App\Http\Resources\Plan;

use App\Models\Plan;
use App\Models\PlanTranslation;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Plan
 */
class PlanDetailResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $locale = app()->getLocale();

        /** @var PlanTranslation|null $translation */
        $translation = $this->translations->firstWhere('locale', $locale);

        return [
            'id' => $this->id,
            'name' => $translation?->name,
            'description' => $translation?->description,
            'price' => "$ " . number_format($this->price, 2) . ' ' . $this->microsite->payment_currency->value,
            'total_duration' => $this->total_duration . ' ' . trans_choice('time_units.' . $this->time_unit->value, $this->total_duration),
            'billing_frequency' => $this->billing_frequency . ' ' . trans_choice('time_units.' . $this->time_unit->value, $this->billing_frequency),
            'created_at' => $this->created_at,
        ];
    }
}

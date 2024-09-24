<?php

namespace App\Http\Resources\Subscription;

use App\Models\Plan;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Plan
 */
class SubscriptionDetailResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $locale = app()->getLocale();

        $translation = $this->translations->firstWhere('locale', $locale);

        return [
            'id' => $this->id,
            'name' => $translation?->name,
            'description' => $translation?->description,
            'price' => $this->price,
            'total_duration' => $this->total_duration . ' ' . trans_choice('time_units.' . $this->time_unit->value, $this->total_duration),
            'billing_frequency' => $this->billing_frequency . ' ' . trans_choice('time_units.' . $this->time_unit->value, $this->billing_frequency),
            'created_at' => $this->created_at,
        ];
    }
}

<?php

namespace App\Http\Resources\Subscription;

use App\Models\Subscription;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Subscription
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
            'total_duration' => $this->total_duration . ' ' . __('time_units.' . $this->time_unit->value),
            'billing_frequency' => $this->billing_frequency . ' ' . __('time_units.' . $this->time_unit->value),
            'created_at' => $this->created_at,
        ];
    }
}

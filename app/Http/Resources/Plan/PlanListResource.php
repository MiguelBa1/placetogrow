<?php

namespace App\Http\Resources\Plan;

use App\Models\Plan;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\App;

/**
 * @mixin Plan
 */
class PlanListResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $currentLocale = App::getLocale();

        $translation = $this->translations->firstWhere('locale', $currentLocale);

        return [
            'id' => $this->id,
            'name' => $translation ? $translation->name : null,
            'price' => "$ " . number_format($this->price),
            'total_duration' => $this->total_duration . ' ' . trans_choice('time_units.' . $this->time_unit->value, $this->total_duration),
            'billing_frequency' => $this->billing_frequency . ' ' . trans_choice('time_units.' . $this->time_unit->value, $this->billing_frequency),
            'time_unit' => __('time_units.' . $this->time_unit->value),
            'created_at' => $this->created_at->format('d/m/Y H:i'),
            'deleted_at' => $this->deleted_at,
        ];
    }
}

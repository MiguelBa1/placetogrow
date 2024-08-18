<?php

namespace App\Http\Resources\Subscription;

use App\Models\Subscription;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\App;

/**
 * @mixin Subscription
 */
class SubscriptionListResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $currentLocale = App::getLocale();

        $translation = $this->translations->firstWhere('locale', $currentLocale);

        return [
            'id' => $this->id,
            'name' => $translation ? $translation->name : null,
            'price' => $this->price,
            'total_duration' => $this->total_duration,
            'billing_frequency' => $this->billing_frequency,
            'billing_unit' => __('billing_units.' . $this->billing_unit->value),
            'created_at' => $this->created_at,
            'deleted_at' => $this->deleted_at,
        ];
    }
}

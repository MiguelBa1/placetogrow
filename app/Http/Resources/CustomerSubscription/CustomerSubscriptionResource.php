<?php

namespace App\Http\Resources\CustomerSubscription;

use App\Models\CustomerSubscription;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin CustomerSubscription
 */
class CustomerSubscriptionResource extends JsonResource
{

    public function toArray(Request $request): array
    {
        return [
            'subscription_name' => $this->subscription->name,
            'microsite_name' => $this->subscription->microsite->name,
            'description' => $this->description,
            'price' => number_format($this->subscription->price / 100, 2),
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'status' => $this->status,
        ];
    }
}

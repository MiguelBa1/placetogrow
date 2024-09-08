<?php

namespace App\Http\Resources\CustomerSubscription;

use App\Models\CustomerSubscription;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\App;

/**
 * @mixin CustomerSubscription
 */
class CustomerSubscriptionResource extends JsonResource
{

    public function toArray(Request $request): array
    {
        $locale = App::getLocale();

        $subscriptionTranslation = $this->subscription->translations
            ->where('locale', $locale)
            ->first();

        $subscriptionName = $subscriptionTranslation?->name ?? $this->subscription->id;

        return [
            'id' => $this->id,
            'subscription_name' => $subscriptionName,
            'microsite_name' => $this->subscription->microsite->name,
            'price' => "$ " . number_format($this->subscription->price),
            'start_date' => date('d/m/Y', strtotime($this->start_date)),
            'end_date' => date('d/m/Y', strtotime($this->end_date)),
            'status' => [
                'label' => __("subscription.statuses.{$this->status}"),
                'value' => $this->status,
            ],
            'created_at' => date('d/m/Y H:i', strtotime($this->created_at)),
        ];
    }
}

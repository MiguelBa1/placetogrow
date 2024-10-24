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
        $locale = App::getLocale();

        $subscriptionTranslation = $this->plan->translations
            ->where('locale', $locale)
            ->first();

        $subscriptionName = $subscriptionTranslation?->name ?? $this->plan->id;

        return [
            'id' => $this->id,
            'subscription_name' => $subscriptionName,
            'microsite_name' => $this->plan->microsite->name,
            'price' => "$ " . number_format($this->plan->price) . " " . $this->currency,
            'start_date' => $this->start_date->format('d/m/Y'),
            'end_date' => $this->end_date->format('d/m/Y'),
            'status' => [
                'label' => __("subscription.statuses.{$this->status}"),
                'value' => $this->status,
            ],
        ];
    }
}

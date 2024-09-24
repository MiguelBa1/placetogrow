<?php

namespace App\Http\Resources\CustomerSubscription;

use App\Models\Subscription;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\App;

/**
 * @mixin Subscription
 */
class CustomerSubscriptionResource extends JsonResource
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
            'price' => "$ " . number_format($this->plan->price),
            'start_date' => date('d/m/Y', strtotime($this->start_date)),
            'end_date' => date('d/m/Y', strtotime($this->end_date)),
            'status' => [
                'label' => __("subscription.statuses.{$this->status}"),
                'value' => $this->status,
            ],
        ];
    }
}

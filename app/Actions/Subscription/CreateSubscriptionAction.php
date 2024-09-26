<?php

namespace App\Actions\Subscription;

use App\Models\Customer;
use App\Models\Microsite;
use App\Models\Plan;
use App\Models\Subscription;
use DateInterval;
use Illuminate\Support\Str;

class CreateSubscriptionAction
{
    public function execute(Plan $plan, Microsite $microsite, Customer $customer, array $additionalData): Subscription
    {
        $start_date = now();
        $end_date = now()->add(DateInterval::createFromDateString("{$plan->total_duration} {$plan->time_unit->value}"));

        return Subscription::create([
            'customer_id' => $customer->id,
            'plan_id' => $plan->id,
            'start_date' => $start_date,
            'end_date' => $end_date,
            'reference' => date('ymdHis') . '-' . strtoupper(Str::random(4)),
            'description' => $customer->name . ' ' . $plan->id,
            'currency' => $microsite->payment_currency->value,
            'additional_data' => $additionalData,
            'price' => $plan->price,
            'billing_frequency' => $plan->billing_frequency,
            'time_unit' => $plan->time_unit->value,
        ]);
    }
}

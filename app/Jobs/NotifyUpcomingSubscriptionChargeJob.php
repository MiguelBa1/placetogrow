<?php

namespace App\Jobs;

use App\Constants\SubscriptionStatus;
use App\Mail\UpcomingSubscriptionChargeMail;
use App\Models\Subscription;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class NotifyUpcomingSubscriptionChargeJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle(): void
    {
        $daysBeforeCharge = (int) config('subscription.notification.days_before_charge');
        $today = Carbon::today();

        $subscriptions = Subscription::select(['id', 'customer_id', 'price', 'currency', 'next_payment_date', 'plan_id'])
            ->whereDate('next_payment_date', '=', $today->addDays($daysBeforeCharge))
            ->where('status', SubscriptionStatus::ACTIVE->value)
            ->with([
                'customer:id,email,name',
                'plan:id,microsite_id',
                'plan.microsite:id,name',
            ])
            ->get();

        foreach ($subscriptions as $subscription) {
            Mail::to($subscription->customer->email)->send(new UpcomingSubscriptionChargeMail($subscription));
        }
    }
}

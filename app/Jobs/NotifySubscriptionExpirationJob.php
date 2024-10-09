<?php

namespace App\Jobs;

use App\Constants\SubscriptionStatus;
use App\Mail\SubscriptionExpirationReminderMail;
use App\Models\Subscription;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class NotifySubscriptionExpirationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle(): void
    {
        $daysBeforeExpiration = (int) config('subscription.notification.days_before_expiration');
        $today = Carbon::today();

        $subscriptions = Subscription::select(['id', 'customer_id', 'price', 'currency', 'end_date', 'plan_id'])
            ->whereDate('end_date', '=', $today->addDays($daysBeforeExpiration))
            ->where('status', SubscriptionStatus::ACTIVE->value)
            ->with([
                'customer:id,email,name',
                'plan:id,microsite_id',
                'plan.microsite:id,name',
            ])
            ->get();

        foreach ($subscriptions as $subscription) {
            Mail::to($subscription->customer->email)
                ->send(new SubscriptionExpirationReminderMail($subscription));
        }
    }
}

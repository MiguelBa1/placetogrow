<?php

namespace App\Console\Commands;

use App\Constants\SubscriptionStatus;
use App\Jobs\CheckSubscriptionStatusJob;
use App\Models\Subscription;
use Illuminate\Console\Command;

class CheckSubscriptionsCommand extends Command
{
    protected $signature = 'app:check-subscriptions-command';

    protected $description = 'Command description';

    public function handle(): void
    {
        $subscriptions = Subscription::query()
            ->where('status', SubscriptionStatus::PENDING->value)
            ->where('created_at', '<', now()->subMinutes(config('subscriptions.check_interval_minutes')))
            ->get();

        if ($subscriptions->isEmpty()) {
            $this->info('No subscriptions to check');
            return;
        }

        foreach ($subscriptions as $subscription) {
            $this->info("Dispatching job to check subscription {$subscription->reference}");
            CheckSubscriptionStatusJob::dispatch($subscription);
        }
    }
}

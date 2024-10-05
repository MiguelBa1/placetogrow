<?php

namespace App\Console\Commands;

use App\Constants\SubscriptionStatus;
use App\Jobs\CollectSubscriptionPaymentJob;
use App\Models\Subscription;
use Illuminate\Console\Command;

class CollectSubscriptionPaymentsCommand extends Command
{
    protected $signature = 'subscriptions:collect-payments';

    protected $description = 'Collect payments for subscriptions with due next payment';

    public function handle(): int
    {
        $subscriptions = Subscription::select('id', 'reference', 'status')
            ->where('status', SubscriptionStatus::ACTIVE->value)
            ->where('next_payment_date', '<=', now())
            ->get();

        if ($subscriptions->isEmpty()) {
            $this->info('No subscriptions due for payment.');
            return Command::SUCCESS;
        }

        foreach ($subscriptions as $subscription) {
            CollectSubscriptionPaymentJob::dispatch($subscription->id);

            $this->info("Dispatched payment collection for subscription: {$subscription->reference}");
        }

        return Command::SUCCESS;
    }
}

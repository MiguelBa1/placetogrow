<?php

namespace App\Jobs;

use App\Contracts\SubscriptionServiceInterface;
use App\Models\Subscription;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;

class CheckSubscriptionStatusJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected Subscription $subscription;

    public function __construct(Subscription $subscription)
    {
        $this->subscription = $subscription;
    }

    public function handle(SubscriptionServiceInterface $subscriptionService): void
    {
        $isSuccessful = $subscriptionService->checkSubscription($this->subscription);

        if ($isSuccessful) {
            Cache::forget('subscription_checked_' . $this->subscription->id);
        }
    }

    public function getSubscriptionId(): int
    {
        return $this->subscription->id;
    }
}

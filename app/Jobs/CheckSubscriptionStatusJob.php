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
    protected SubscriptionServiceInterface $subscriptionService;

    public function __construct(Subscription $subscription)
    {
        $this->subscription = $subscription;
        $this->subscriptionService = app(SubscriptionServiceInterface::class);
    }

    public function handle(): void
    {
        $isSuccessful = $this->subscriptionService->checkSubscription($this->subscription);

        if ($isSuccessful) {
            Cache::forget('subscription_checked_' . $this->subscription->id);
        }
    }

    public function getSubscriptionId(): int
    {
        return $this->subscription->id;
    }
}

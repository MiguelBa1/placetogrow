<?php

namespace Tests\Feature\Jobs;

use App\Constants\SubscriptionStatus;
use App\Jobs\CheckSubscriptionStatusJob;
use App\Models\Plan;
use App\Models\Subscription;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;
use Tests\Traits\PlaceToPayMockTrait;

class CheckSubscriptionStatusJobTest extends TestCase
{
    use RefreshDatabase, PlaceToPayMockTrait;

    public function test_job_processes_subscription_successfully()
    {
        $this->fakeSubscriptionCheckApproved();
        Cache::spy();

        $plan = Plan::factory()->create();
        $subscription = Subscription::factory()->create([
            'plan_id' => $plan->id,
            'request_id' => 'test_request_id',
            'status' => SubscriptionStatus::PENDING->value,
            'created_at' => now()->subMinutes(config('subscriptions.check_interval_minutes'))->subSecond(),
        ]);

        $job = new CheckSubscriptionStatusJob($subscription);
        $job->withFakeQueueInteractions();
        $job->handle();

        Cache::shouldHaveReceived('forget')->once()->with('subscription_checked_' . $subscription->id);
        $subscription->refresh();

        $this->assertEquals(SubscriptionStatus::ACTIVE->value, $subscription->status);
    }
}

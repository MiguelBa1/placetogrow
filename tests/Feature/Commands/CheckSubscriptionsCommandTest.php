<?php

namespace Tests\Feature\Commands;

use App\Constants\SubscriptionStatus;
use App\Jobs\CheckSubscriptionStatusJob;
use App\Models\Subscription;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Bus;
use Tests\TestCase;

class CheckSubscriptionsCommandTest extends TestCase
{
    use RefreshDatabase;

    public function test_no_subscriptions_to_check_message_is_displayed_when_no_subscriptions_found()
    {
        Bus::fake();

        $this->artisan('check:subscriptions')
            ->expectsOutput('No subscriptions to check')
            ->assertExitCode(0);

        Bus::assertNotDispatched(CheckSubscriptionStatusJob::class);
    }

    public function test_dispatches_jobs_for_pending_subscriptions()
    {
        Bus::fake();

        $subscription = Subscription::factory()->create([
            'status' => SubscriptionStatus::PENDING->value,
            'created_at' => now()->subMinutes(config('subscription.check_interval_minutes'))->subSecond(),
        ]);

        $this->artisan('check:subscriptions');

        Bus::assertDispatched(CheckSubscriptionStatusJob::class, function ($job) use ($subscription) {
            return $job->getSubscriptionId() === $subscription->id;
        });
    }

    public function test_does_not_dispatch_jobs_for_recent_subscriptions()
    {
        Bus::fake();

        Subscription::factory()->create([
            'status' => SubscriptionStatus::PENDING->value,
            'created_at' => now(), // Too recent to be checked
        ]);

        Artisan::call('check:subscriptions');

        Bus::assertNotDispatched(CheckSubscriptionStatusJob::class);
    }
}

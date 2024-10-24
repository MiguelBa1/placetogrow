<?php

namespace Tests\Feature\Jobs;

use App\Constants\PaymentStatus;
use App\Constants\SubscriptionStatus;
use App\Constants\TimeUnit;
use App\Contracts\PlaceToPayServiceInterface;
use App\Jobs\CollectSubscriptionPaymentJob;
use App\Models\Microsite;
use App\Models\Plan;
use App\Models\Subscription;
use Exception;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;
use Tests\Traits\PlaceToPayMockTrait;

class CollectSubscriptionPaymentJobTest extends TestCase
{
    use RefreshDatabase, PlaceToPayMockTrait;

    private Subscription $subscription;

    public function setUp(): void
    {
        parent::setUp();

        Queue::fake();

        $this->subscription = Subscription::factory()->create([
            'status' => SubscriptionStatus::ACTIVE->value,
        ]);
    }

    public function test_collect_subscription_payment_job_executes_successfully(): void
    {
        $this->fakeCollectSubscriptionPaymentSuccess();

        $this->subscription->update([
            'next_payment_date' => now()->addDay(),
            'end_date' => now()->addDays(10),
            'billing_frequency' => 2,
            'time_unit' => TimeUnit::DAYS,
        ]);

        $job = new CollectSubscriptionPaymentJob($this->subscription->id);
        $job->handle(app(PlaceToPayServiceInterface::class));

        Queue::assertNothingPushed();
        $this->assertEquals(SubscriptionStatus::ACTIVE->value, $this->subscription->fresh()->status);

        $this->assertDatabaseHas('payments', [
            'status' => PaymentStatus::APPROVED->value,
        ]);

        $this->assertDatabaseHas('subscriptions', [
            'status' => SubscriptionStatus::ACTIVE->value,
        ]);
    }

    public function test_collect_subscription_payment_job_retries_on_error(): void
    {
        $this->fakeCollectSubscriptionPaymentFailed();

        $microsite = Microsite::factory()->create([
            'settings' => [
                'retry' => [
                    'max_retries' => 3,
                    'retry_backoff' => 1,
                ],
            ],
        ]);

        $plan = Plan::factory()->create([
            'microsite_id' => $microsite->id,
        ]);

        $this->subscription->plan_id = $plan->id;
        $this->subscription->save();

        $job = new CollectSubscriptionPaymentJob($this->subscription->id);
        $job->withFakeQueueInteractions();
        $job->handle(app(PlaceToPayServiceInterface::class));

        $job->assertReleased();
    }

    public function test_collect_subscription_payment_job_deactivates_subscription_after_max_retries(): void
    {
        $this->fakeCollectSubscriptionPaymentFailed();

        $job = new CollectSubscriptionPaymentJob($this->subscription->id);
        $job->failed(new Exception('Payment failed'));

        $this->assertEquals(SubscriptionStatus::INACTIVE->value, $this->subscription->fresh()->status);
    }

    public function test_collect_subscription_payment_job_uses_correct_max_retries(): void
    {
        $microsite = Microsite::factory()->create([
            'settings' => [
                'retry' => [
                    'max_retries' => 5,
                    'retry_backoff' => '1 hour',
                ],
            ],
        ]);

        $plan = Plan::factory()->create([
            'microsite_id' => $microsite->id,
        ]);

        $this->subscription->plan_id = $plan->id;
        $this->subscription->save();

        $job = new CollectSubscriptionPaymentJob($this->subscription->id);

        $this->assertEquals(5, $job->tries());
    }

    public function test_collect_subscription_payment_job_deactivates_subscription_when_next_payment_exceeds_end_date(): void
    {
        $this->fakeCollectSubscriptionPaymentSuccess();

        $this->subscription->update([
            'next_payment_date' => now(),
            'end_date' => now()->addDay(),
            'billing_frequency' => 5,
            'time_unit' => TimeUnit::DAYS,
        ]);

        $job = new CollectSubscriptionPaymentJob($this->subscription->id);
        $job->handle(app(PlaceToPayServiceInterface::class));

        $this->subscription->refresh();

        $this->assertEquals(SubscriptionStatus::INACTIVE->value, $this->subscription->status);
        $this->assertDatabaseHas('subscriptions', [
            'id' => $this->subscription->id,
            'status' => SubscriptionStatus::INACTIVE->value,
            'status_message' => 'Subscription has reached its end date',
        ]);
    }
}

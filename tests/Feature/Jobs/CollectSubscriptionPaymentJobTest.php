<?php

namespace Tests\Feature\Jobs;

use App\Actions\Payment\CreatePaymentAction;
use App\Actions\Payment\UpdatePaymentFromP2PResponse;
use App\Constants\PaymentStatus;
use App\Constants\SubscriptionStatus;
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

        $job = new CollectSubscriptionPaymentJob($this->subscription->id);
        $job->handle(app(PlaceToPayServiceInterface::class), app(CreatePaymentAction::class), app(UpdatePaymentFromP2PResponse::class));

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
        $job->withFakeQueueInteractions();
        $job->handle(app(PlaceToPayServiceInterface::class), app(CreatePaymentAction::class), app(UpdatePaymentFromP2PResponse::class));

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
}

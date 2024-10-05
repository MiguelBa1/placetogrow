<?php

namespace Tests\Feature\Commands;

use App\Constants\SubscriptionStatus;
use App\Jobs\CollectSubscriptionPaymentJob;
use App\Models\Subscription;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Bus;
use Tests\TestCase;

class CollectSubscriptionPaymentsCommandTest extends TestCase
{

    use RefreshDatabase;

    public function test_dispatches_payment_jobs_for_due_subscriptions()
    {
        $dueSubscription = Subscription::factory()->create([
            'status' => SubscriptionStatus::ACTIVE->value,
            'next_payment_date' => now()->subDay(),
        ]);

        $nonDueSubscription = Subscription::factory()->create([
            'status' => SubscriptionStatus::ACTIVE->value,
            'next_payment_date' => now()->addDay(),
        ]);

        Bus::fake();

        $this->artisan('subscriptions:collect-payments')
            ->expectsOutput("Dispatched payment collection for subscription: {$dueSubscription->reference}")
            ->assertExitCode(0);

        Bus::assertDispatched(CollectSubscriptionPaymentJob::class, function ($job) use ($dueSubscription) {
            return $job->getSubscriptionId() === $dueSubscription->id;
        });

        Bus::assertNotDispatched(CollectSubscriptionPaymentJob::class, function ($job) use ($nonDueSubscription) {
            return $job->getSubscriptionId() === $nonDueSubscription->id;
        });
    }

    public function test_outputs_message_when_no_due_subscriptions()
    {
        Subscription::factory()->create([
            'status' => SubscriptionStatus::ACTIVE->value,
            'next_payment_date' => now()->addDay(),
        ]);

        Bus::fake();

        $this->artisan('subscriptions:collect-payments')
            ->expectsOutput('No subscriptions due for payment.')
            ->assertExitCode(0);

        Bus::assertNotDispatched(CollectSubscriptionPaymentJob::class);
    }
}

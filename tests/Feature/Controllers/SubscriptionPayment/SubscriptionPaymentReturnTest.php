<?php

namespace Tests\Feature\Controllers\SubscriptionPayment;

use App\Constants\MicrositeType;
use App\Constants\SubscriptionStatus;
use App\Mail\SubscriptionCreatedMail;
use App\Models\Microsite;
use App\Models\Subscription;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;
use Mockery;
use Tests\TestCase;
use Tests\Traits\CreatesMicrosites;
use Tests\Traits\PlaceToPayMockTrait;

class SubscriptionPaymentReturnTest extends TestCase
{
    use RefreshDatabase, CreatesMicrosites, PlaceToPayMockTrait;

    private Microsite $subscriptionMicrosite;

    public function setUp(): void
    {
        parent::setUp();

        $this->subscriptionMicrosite = $this->createMicrositeWithFields(MicrositeType::SUBSCRIPTION);
    }

    public function test_return_after_subscription_payment(): void
    {
        Mail::fake();

        $this->fakeSubscriptionCheckApproved();
        Cache::spy();

        $subscriptionPaymentReference = 'test_reference';

        $subscription = Subscription::factory()->create([
            'reference' => $subscriptionPaymentReference,
            'request_id' => 'test_request_id',
            'status' => SubscriptionStatus::PENDING->value,
        ]);

        $response = $this->get(route('subscription-payments.return', $subscriptionPaymentReference));

        $response->assertOk();
        $response->assertInertia(
            fn ($page) => $page
                ->component('Payments/Return')
                ->has('subscription')
                ->has('customer')
        );

        Mail::assertQueued(SubscriptionCreatedMail::class, function ($mail) use ($subscription) {
            return $mail->hasTo($subscription->customer->email) &&
                $mail->subscription->is($subscription);
        });

        Cache::shouldHaveReceived('put')
            ->once()
            ->with('subscription_checked_' . $subscription->id, true, Mockery::any());
    }

    public function test_return_after_subscription_payment_error(): void
    {
        $this->fakeSubscriptionCheckFailed();

        $subscriptionPaymentReference = 'test_reference';
        Subscription::factory()->create([
            'reference' => $subscriptionPaymentReference,
            'request_id' => 'test_request_id',
            'status' => SubscriptionStatus::PENDING->value,
        ]);

        $response = $this->get(route('subscription-payments.return', $subscriptionPaymentReference));

        $response->assertInertia(
            fn ($page) => $page
                ->component('Payments/Return')
                ->has('error')
        );
    }

    public function test_return_rejected_subscription_payment(): void
    {
        $this->fakeSubscriptionCheckRejected();
        Cache::spy();

        $subscriptionPaymentReference = 'test_reference';

        $subscription = Subscription::factory()->create([
            'reference' => $subscriptionPaymentReference,
            'request_id' => 'test_request_id',
            'status' => SubscriptionStatus::PENDING->value,
        ]);

        $response = $this->get(route('subscription-payments.return', $subscriptionPaymentReference));

        $response->assertOk();
        $response->assertInertia(
            fn ($page) => $page
                ->component('Payments/Return')
                ->has('subscription')
                ->has('customer')
        );

        Cache::shouldHaveReceived('put')
            ->once()
            ->with('subscription_checked_' . $subscription->id, true, Mockery::any());
    }

    public function test_already_active_subscription_payment(): void
    {
        $this->fakeSubscriptionCheckApproved();
        Cache::spy();

        $subscriptionPaymentReference = 'test_reference';

        $subscription = Subscription::factory()->create([
            'reference' => $subscriptionPaymentReference,
            'request_id' => 'test_request_id',
            'status' => SubscriptionStatus::ACTIVE->value,
        ]);

        $this->get(route('subscription-payments.return', $subscriptionPaymentReference));

        $this->assertDatabaseHas('subscriptions', [
            'id' => $subscription->id,
            'status' => SubscriptionStatus::ACTIVE->value,
        ]);
    }

    public function test_return_after_subscription_payment_using_cache(): void
    {
        $this->fakeSubscriptionCheckPending();
        Cache::spy();

        $subscriptionPaymentReference = 'test_reference';

        $subscription = Subscription::factory()->create([
            'reference' => $subscriptionPaymentReference,
            'request_id' => 'test_request_id',
            'status' => SubscriptionStatus::PENDING->value,
        ]);

        $response = $this->get(route('subscription-payments.return', $subscriptionPaymentReference));

        $response->assertOk();
        $response->assertInertia(
            fn ($page) => $page
                ->component('Payments/Return')
                ->has('subscription')
                ->has('customer')
        );

        Cache::shouldHaveReceived('get')
            ->once()
            ->with('subscription_checked_' . $subscription->id);
    }
}

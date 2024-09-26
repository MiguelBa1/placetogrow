<?php

namespace Tests\Feature\Controllers\SubscriptionPayment;

use App\Constants\MicrositeType;
use App\Constants\SubscriptionStatus;
use App\Models\CustomerSubscription;
use App\Models\Microsite;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
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
        $this->fakeSubscriptionCheckApproved();
        Cache::spy();

        $subscriptionPaymentReference = 'test_reference';

        $customerSubscription = CustomerSubscription::factory()->create([
            'reference' => $subscriptionPaymentReference,
            'request_id' => 'test_request_id',
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
            ->with('subscription_status_' . $customerSubscription->id, SubscriptionStatus::ACTIVE->value, Mockery::any());
    }

    public function test_return_after_subscription_payment_error(): void
    {
        $this->fakeSubscriptionCheckFailed();

        $subscriptionPaymentReference = 'test_reference';
        CustomerSubscription::factory()->create([
            'reference' => $subscriptionPaymentReference,
            'request_id' => 'test_request_id',
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

        $customerSubscription = CustomerSubscription::factory()->create([
            'reference' => $subscriptionPaymentReference,
            'request_id' => 'test_request_id',
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
            ->with('subscription_status_' . $customerSubscription->id, SubscriptionStatus::INACTIVE->value, Mockery::any());
    }

    public function test_already_active_subscription_payment(): void
    {
        $this->fakeSubscriptionCheckApproved();
        Cache::spy();

        $subscriptionPaymentReference = 'test_reference';

        $customerSubscription = CustomerSubscription::factory()->create([
            'reference' => $subscriptionPaymentReference,
            'request_id' => 'test_request_id',
            'status' => SubscriptionStatus::ACTIVE->value,
        ]);

        $this->get(route('subscription-payments.return', $subscriptionPaymentReference));

        $this->assertDatabaseHas('customer_subscription', [
            'id' => $customerSubscription->id,
            'status' => SubscriptionStatus::ACTIVE->value,
        ]);
    }

    public function test_return_after_subscription_payment_using_cache(): void
    {
        $subscriptionPaymentReference = 'test_reference';
        $cachedStatus = SubscriptionStatus::PENDING->value;

        $customerSubscription = CustomerSubscription::factory()->create([
            'reference' => $subscriptionPaymentReference,
            'request_id' => 'test_request_id',
            'status' => SubscriptionStatus::PENDING->value,
        ]);

        Cache::shouldReceive('get')
            ->once()
            ->with('subscription_status_' . $customerSubscription->id)
            ->andReturn($cachedStatus);

        $response = $this->get(route('subscription-payments.return', $subscriptionPaymentReference));

        $response->assertOk();
        $response->assertInertia(
            fn ($page) => $page
                ->component('Payments/Return')
                ->has('subscription')
                ->has('customer')
        );
    }

}

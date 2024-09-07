<?php

namespace Tests\Feature\Controllers\SubscriptionPayment;

use App\Constants\MicrositeType;
use App\Constants\SubscriptionStatus;
use App\Models\Microsite;
use App\Models\Subscription;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;
use Tests\Traits\CreatesMicrosites;
use Tests\Traits\PlaceToPayMockTrait;

class SubscriptionPaymentTest extends TestCase
{
    use RefreshDatabase, CreatesMicrosites, PlaceToPayMockTrait;

    private Microsite $subscriptionMicrosite;
    private Subscription $subscription;

    public function setUp(): void
    {
        parent::setUp();

        $this->subscriptionMicrosite = $this->createMicrositeWithFields(MicrositeType::SUBSCRIPTION);
        $this->subscription = Subscription::factory()->create([
            'microsite_id' => $this->subscriptionMicrosite->id,
        ]);
    }


    public function test_customer_can_view_payment_page(): void
    {
        $response = $this->get(route('subscription-payments.show', $this->subscriptionMicrosite));

        $response->assertOk();
        $response->assertInertia(
            fn (Assert $page) => $page
            ->component('Payments/Show')
            ->has('microsite')
            ->has('fields')
            ->has('subscriptions')
        );
    }

    public function test_store_subscription_payment(): void
    {
        $this->fakePaymentCreationSuccess();

        $response = $this->post(route('subscription-payments.store', [
            'microsite' => $this->subscriptionMicrosite,
            'subscription' => $this->subscription,
        ]), [
            'name' => 'John',
            'last_name' => 'Doe',
            'email' => 'test@mail.com',
            'document_number' => '123456789',
            'document_type' => 'CC',
            'phone' => '3001234567',
        ]);

        $response->assertRedirect('/success');

        $this->assertDatabaseHas('customers', [
            'name' => 'John',
            'last_name' => 'Doe',
            'document_type' => 'CC',
            'document_number' => '123456789',
            'phone' => '3001234567',
            'email' => 'test@mail.com',
        ]);

        $this->assertDatabaseHas('customer_subscription', [
            'subscription_id' => $this->subscription->id,
            'status' => SubscriptionStatus::PENDING->value,
        ]);
    }

    public function test_store_subscription_payment_error(): void
    {
        $this->fakePaymentCreationFailed();

        $response = $this->post(route('subscription-payments.store', [
            'microsite' => $this->subscriptionMicrosite,
            'subscription' => $this->subscription,
        ]), [
            'name' => 'John',
            'last_name' => 'Doe',
            'document_type' => 'CC',
            'document_number' => '123456789',
            'phone' => '3001234567',
            'email' => 'test@mail.com',
        ]);

        $response->assertRedirect(route('subscription-payments.show', $this->subscriptionMicrosite));
        $response->assertSessionHasErrors();
    }

}

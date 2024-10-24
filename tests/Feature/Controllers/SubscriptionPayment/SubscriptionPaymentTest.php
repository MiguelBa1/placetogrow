<?php

namespace Tests\Feature\Controllers\SubscriptionPayment;

use App\Constants\MicrositeType;
use App\Constants\SubscriptionStatus;
use App\Models\Microsite;
use App\Models\Plan;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;
use Tests\Traits\CreatesMicrosites;
use Tests\Traits\PlaceToPayMockTrait;

class SubscriptionPaymentTest extends TestCase
{
    use RefreshDatabase, CreatesMicrosites, PlaceToPayMockTrait;

    private Microsite $subscriptionMicrosite;
    private Plan $subscription;

    public function setUp(): void
    {
        parent::setUp();

        $this->subscriptionMicrosite = $this->createMicrositeWithFields(MicrositeType::SUBSCRIPTION);
        $this->plan = Plan::factory()->create([
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

        // Add a dynamic field to the microsite
        $this->subscriptionMicrosite->fields()->create([
            'name' => 'color',
            'type' => 'text',
            'label' => 'Color',
            'modifiable' => true,
        ]);

        $response = $this->post(route('subscription-payments.store', [
            'microsite' => $this->subscriptionMicrosite,
            'plan' => $this->plan,
        ]), [
            'name' => 'John',
            'last_name' => 'Doe',
            'email' => 'test@mail.com',
            'document_number' => '123456789',
            'document_type' => 'CC',
            'phone' => '3001234567',
            'color' => '#000000',
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

        $this->assertDatabaseHas('subscriptions', [
            'plan_id' => $this->plan->id,
            'status' => SubscriptionStatus::PENDING->value,
        ]);
    }

    public function test_store_subscription_payment_error(): void
    {
        $this->fakePaymentCreationFailed();

        $response = $this->post(route('subscription-payments.store', [
            'microsite' => $this->subscriptionMicrosite,
            'plan' => $this->plan,
        ]), [
            'name' => 'John',
            'last_name' => 'Doe',
            'document_type' => 'CC',
            'document_number' => '123456789',
            'phone' => '3001234567',
            'email' => 'test@mail.com',
        ]);

        $response->assertRedirect(url()->previous());
        $response->assertSessionHasErrors();
    }

}

<?php

namespace Tests\Feature\Controllers\CustomerSubscription;

use App\Constants\SubscriptionStatus;
use App\Mail\CustomerSubscriptionLinkMail;
use App\Models\Customer;
use App\Models\CustomerSubscription;
use App\Models\Microsite;
use App\Models\Subscription;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class CustomerSubscriptionControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_displays_the_customer_subscription_index_page()
    {
        $response = $this->get(route('subscriptions.index'));

        $response->assertOk();
        $response->assertInertia(
            fn (Assert $page) =>
            $page->component('CustomerSubscriptions/Index')
        );
    }

    public function test_sends_the_subscription_link_via_email()
    {
        Mail::fake();

        $customer = Customer::factory()->create([
            'email' => 'test@example.com',
            'document_number' => '1234567890',
        ]);

        $requestData = [
            'email' => $customer->email,
            'document_number' => $customer->document_number,
        ];

        $response = $this->post(route('subscriptions.send-link'), $requestData);

        $response->assertRedirect();

        Mail::assertQueued(CustomerSubscriptionLinkMail::class, function ($mail) use ($requestData) {
            return $mail->hasTo($requestData['email']);
        });
    }

    public function test_displays_subscriptions_with_a_valid_signed_url()
    {
        $microsite = Microsite::factory()->create();
        $customer = Customer::factory()->create([
            'email' => 'test@example.com',
            'document_number' => '1234567890',
        ]);

        $subscription = Subscription::factory()->create(['microsite_id' => $microsite->id]);
        CustomerSubscription::factory()->create([
            'customer_id' => $customer->id,
            'subscription_id' => $subscription->id,
            'status' => SubscriptionStatus::ACTIVE,
        ]);

        $url = URL::temporarySignedRoute('subscriptions.show', now()->addMinutes(60), [
            'email' => 'test@example.com',
            'document_number' => '1234567890',
        ]);

        $response = $this->get($url);

        $response->assertOk();
        $response->assertInertia(
            fn (Assert $page) =>
            $page->component('CustomerSubscriptions/Show')
                ->has('subscriptions')
                ->where('customer.email', 'test@example.com')
                ->where('customer.document_number', '1234567890')
        );
    }

    public function test_aborts_if_the_link_is_invalid_or_expired()
    {
        $url = route('subscriptions.show', [
            'email' => 'test@example.com',
            'document_number' => '1234567890',
        ]);

        $response = $this->get($url);

        $response->assertForbidden();
        $response->assertSee(__('message.invalid_link'));
    }

    public function test_cancels_a_subscription_successfully()
    {
        $customer = Customer::factory()->create([
            'email' => 'test@example.com',
            'document_number' => '1234567890',
        ]);

        $subscription = Subscription::factory()->create();
        $customerSubscription = CustomerSubscription::factory()->create([
            'customer_id' => $customer->id,
            'subscription_id' => $subscription->id,
            'status' => SubscriptionStatus::ACTIVE,
        ]);

        $requestData = [
            'email' => $customer->email,
            'document_number' => $customer->document_number,
        ];

        $response = $this->post(route('subscriptions.cancel', $customerSubscription->id), $requestData);

        $response->assertRedirect();
        $this->assertDatabaseHas('customer_subscription', [
            'id' => $customerSubscription->id,
            'status' => SubscriptionStatus::INACTIVE,
        ]);
    }
}

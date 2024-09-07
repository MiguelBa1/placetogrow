<?php

namespace Tests\Feature\Controllers\Payment;

use App\Constants\DocumentType;
use App\Constants\MicrositeType;
use App\Constants\PaymentStatus;
use App\Models\Microsite;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;
use Tests\Traits\CreatesMicrosites;
use Tests\Traits\PlaceToPayMockTrait;

class BasicPaymentTest extends TestCase
{
    use RefreshDatabase, CreatesMicrosites, PlaceToPayMockTrait;

    private Microsite $basicMicrosite;

    public function setUp(): void
    {
        parent::setUp();

        Storage::fake('microsites_logos');
        Storage::fake('category_icons');

        $this->basicMicrosite = $this->createMicrositeWithFields(MicrositeType::BASIC);
    }

    public function test_customer_can_view_payment_page(): void
    {
        $response = $this->get(route('payments.show', $this->basicMicrosite));

        $response->assertOk();
        $response->assertInertia(
            fn (Assert $page) => $page
            ->component('Payments/Show')
            ->has('microsite')
            ->has('fields')
        );
    }

    public function test_store_payment(): void
    {
        $this->fakePaymentCreationSuccess();

        $response = $this->post(route('payments.store', $this->basicMicrosite), [
            'name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john@example.com',
            'document_number' => '123456789',
            'document_type' => DocumentType::CC->value,
            'phone' => '3001234567',
            'amount' => 10000,
            'payment_description' => 'Test payment',
        ]);

        $response->assertRedirect('/success');
        $this->assertDatabaseHas('customers', [
            'name' => 'John',
            'last_name' => 'Doe',
            'document_type' => 'CC',
            'document_number' => '123456789',
            'phone' => '3001234567',
            'email' => 'john@example.com'
        ]);
        $this->assertDatabaseHas('payments', [
            'request_id' => 'test_request_id',
            'status' => PaymentStatus::PENDING->value,
            'amount' => 10000,
        ]);
    }

    public function test_store_payment_error(): void
    {
        $this->fakePaymentCreationFailed();

        $response = $this->post(route('payments.store', $this->basicMicrosite), [
            'name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john@example.com',
            'document_number' => '123456789',
            'document_type' => 'CC',
            'phone' => '3001234567',
            'amount' => 10000,
            'payment_description' => 'Test payment',
        ]);

        $response->assertRedirect(route('payments.show', $this->basicMicrosite));
        $response->assertSessionHasErrors();
    }

}

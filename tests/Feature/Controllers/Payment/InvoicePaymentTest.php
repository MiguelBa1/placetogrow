<?php

namespace Tests\Feature\Controllers\Payment;

use App\Constants\DocumentType;
use App\Constants\MicrositeType;
use App\Constants\PaymentStatus;
use App\Models\Microsite;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;
use Tests\Traits\CreatesMicrosites;

class InvoicePaymentTest extends TestCase
{
    use RefreshDatabase, CreatesMicrosites;

    private Microsite $invoiceMicrosite;

    public function setUp(): void
    {
        parent::setUp();

        Storage::fake('microsites_logos');
        Storage::fake('category_icons');

        $this->invoiceMicrosite = $this->createMicrositeWithFields(MicrositeType::INVOICE);

        $this->invoiceMicrosite->invoices()->create([
            'reference' => 'test_reference',
            'document_type' => DocumentType::CC->value,
            'document_number' => '123456789',
            'name' => 'test_name',
            'last_name' => 'test_last_name',
            'email' => 'test@mail.com',
            'phone' => '123456789',
            'amount' => 1000,
            'expiration_date' => now()->addDays(1),
        ]);
    }

    public function test_guest_can_view_payment_page(): void
    {
        $response = $this->get(route('payments.show', $this->invoiceMicrosite));

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
        Http::fake([
            config('placetopay.url') . '/*' => Http::response([
                'processUrl' => '/success',
                'requestId' => 'test_request_id',
                'status' => [
                    'status' => PaymentStatus::PENDING->value,
                    'message' => 'Payment pending',
                    'date' => now()->toIso8601String(),
                ],
            ])
        ]);

        $response = $this->post(route('payments.store', $this->invoiceMicrosite), [
            'reference' => 'test_reference',
            'document_number' => '123456789',
        ]);

        $response->assertRedirect('/success');
        $this->assertDatabaseHas('guests', [
            'name' => 'test_name',
            'last_name' => 'test_last_name',
            'document_type' => 'CC',
            'document_number' => '123456789',
            'phone' => '123456789',
        ]);

        $this->assertDatabaseHas('payments', [
            'request_id' => 'test_request_id',
            'status' => PaymentStatus::PENDING->value,
            'amount' => 1000,
        ]);
    }

    public function test_store_payment_with_invalid_invoice(): void
    {
        $response = $this->post(route('payments.store', $this->invoiceMicrosite), [
            'reference' => 'test_reference',
            'document_number' => '12345', // Invalid document number
        ]);

        $response->assertFound();
        $response->assertSessionHasErrors('payment');
    }
}

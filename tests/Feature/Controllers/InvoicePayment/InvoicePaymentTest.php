<?php

namespace Tests\Feature\Controllers\InvoicePayment;

use App\Constants\DocumentType;
use App\Constants\InvoiceStatus;
use App\Constants\MicrositeType;
use App\Constants\PaymentStatus;
use App\Models\Invoice;
use App\Models\Microsite;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;
use Tests\Traits\CreatesMicrosites;
use Tests\Traits\PlaceToPayMockTrait;

class InvoicePaymentTest extends TestCase
{
    use RefreshDatabase, CreatesMicrosites, PlaceToPayMockTrait;

    private Microsite $invoiceMicrosite;
    private Invoice $invoice;

    public function setUp(): void
    {
        parent::setUp();

        $this->invoiceMicrosite = $this->createMicrositeWithFields(MicrositeType::INVOICE);

        $this->invoice = Invoice::factory()->create([
            'microsite_id' => $this->invoiceMicrosite->id,
            'reference' => 'test_reference',
            'status' => PaymentStatus::PENDING->value,
            'document_type' => DocumentType::CC->value,
            'document_number' => '123456789',
            'name' => 'test_name',
            'last_name' => 'test_last_name',
            'email' => 'test@mail.com',
            'phone' => '123456789',
            'amount' => 1000,
            'expiration_date' => now()->addDays(),
        ]);
    }

    public function test_customer_can_view_payment_page(): void
    {
        $response = $this->get(route('invoice-payments.show', $this->invoiceMicrosite));

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

        $response = $this->post(route('invoice-payments.store', [
            'microsite' => $this->invoiceMicrosite,
            'invoice' => $this->invoice,
        ]), [
            'reference' => 'test_reference',
            'document_number' => '123456789',
        ]);

        $response->assertRedirect('/success');

        $this->assertDatabaseHas('customers', [
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
        $invalidInvoice = Invoice::factory()->create([
            'reference' => 'invalid_reference',
            'document_number' => 'invalid_doc_number',
            'microsite_id' => $this->invoiceMicrosite->id,
        ]);

        $response = $this->post(route('invoice-payments.store', [
            'microsite' => $this->invoiceMicrosite,
            'invoice' => $invalidInvoice,
        ]), [
            'reference' => 'test_reference',
            'document_number' => '123456789',
        ]);

        $response->assertFound();
        $response->assertSessionHasErrors('payment');
    }

    public function test_get_pending_invoices(): void
    {
        Invoice::factory()->create([
            'microsite_id' => $this->invoiceMicrosite->id,
            'reference' => 'another_reference',
            'status' => InvoiceStatus::PAID->value,
            'document_type' => DocumentType::CC->value,
            'document_number' => '123456789',
            'name' => 'test_name',
            'last_name' => 'test_last_name',
            'amount' => 1500,
            'expiration_date' => now()->addDays(),
        ]);

        $response = $this->get(route('invoice-payments.pending-invoices', [
            'microsite' => $this->invoiceMicrosite->slug,
            'document_number' => '123456789',
            'reference' => 'test_reference',
        ]));

        $response->assertOk();

        $response->assertJsonCount(1, 'data');

        $response->assertJsonFragment([
            'reference' => 'test_reference',
            'name' => 'test_name test_last_name',
            'amount' => '$1,000.00',
            'late_fee' => '$0.00',
            'total_amount' => '$1,000.00',
        ]);

        $response->assertJsonMissing([
            'reference' => 'another_reference',
        ]);
    }
}

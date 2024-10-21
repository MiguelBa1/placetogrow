<?php

namespace Tests\Feature\Controllers\InvoicePayment;

use App\Constants\InvoiceStatus;
use App\Constants\MicrositeType;
use App\Constants\PaymentStatus;
use App\Models\Invoice;
use App\Models\Microsite;
use App\Models\Payment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Inertia\Testing\AssertableInertia as Assert;
use Mockery;
use Tests\TestCase;
use Tests\Traits\CreatesMicrosites;
use Tests\Traits\PlaceToPayMockTrait;

class InvoicePaymentReturnTest extends TestCase
{
    use RefreshDatabase, CreatesMicrosites, PlaceToPayMockTrait;

    private Microsite $invoiceMicrosite;

    public function setUp(): void
    {
        parent::setUp();

        $this->invoiceMicrosite = $this->createMicrositeWithFields(MicrositeType::INVOICE);
    }

    public function test_return_after_approved_payment(): void
    {
        $this->fakePaymentCheckApproved();

        $paymentReference = 'test_reference';

        $pendingInvoice = Invoice::factory()->create([
            'reference' => $paymentReference,
            'status' => PaymentStatus::PENDING->value,
            'microsite_id' => $this->invoiceMicrosite->id,
        ]);

        $payment = Payment::factory()->create([
            'microsite_id' => $this->invoiceMicrosite->id,
            'invoice_id' => $pendingInvoice->id,
            'reference' => $paymentReference,
            'request_id' => 'test_request_id',
            'status' => PaymentStatus::PENDING->value,
        ]);

        Cache::spy();

        $response = $this->get(route('invoice-payments.return', $paymentReference));

        $response->assertOk();

        $response->assertInertia(
            fn (Assert $page) => $page
                ->component('Payments/Return')
                ->has('payment')
                ->has('customer')
                ->has('micrositeName')
        );

        $pendingInvoice->refresh();

        $this->assertEquals(InvoiceStatus::PAID->value, $pendingInvoice->status->value);

        Cache::shouldHaveReceived('put')
            ->once()
            ->with('payment_checked_' . $payment->id, true, Mockery::any());
    }

    public function test_return_after_payment_error(): void
    {
        $this->fakePaymentCheckFailed();

        $paymentReference = 'test_reference';
        Payment::factory()->create([
            'reference' => $paymentReference,
            'request_id' => 'test_request_id',
            'microsite_id' => $this->invoiceMicrosite->id,
            'status' => PaymentStatus::PENDING->value,
        ]);

        $response = $this->get(route('invoice-payments.return', $paymentReference));

        $response->assertRedirect(route('invoice-payments.show', $this->invoiceMicrosite));
        $response->assertSessionHasErrors();
    }

    public function test_invoice_status_is_not_updated_when_payment_is_rejected(): void
    {
        $this->fakePaymentCheckRejected();

        $paymentReference = 'test_reference';

        $pendingInvoice = Invoice::factory()->create([
            'reference' => $paymentReference,
            'status' => PaymentStatus::PENDING->value,
            'microsite_id' => $this->invoiceMicrosite->id,
        ]);

        Payment::factory()->create([
            'microsite_id' => $this->invoiceMicrosite->id,
            'invoice_id' => $pendingInvoice->id,
            'reference' => $paymentReference,
            'request_id' => 'test_request_id',
            'status' => PaymentStatus::PENDING->value,
        ]);

        $response = $this->get(route('invoice-payments.return', $paymentReference));

        $response->assertOk();

        $pendingInvoice->refresh();

        $this->assertEquals(InvoiceStatus::PENDING->value, $pendingInvoice->status->value);
    }
}

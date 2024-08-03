<?php

namespace Tests\Feature\Controllers\Payment;

use App\Constants\MicrositeType;
use App\Constants\PaymentStatus;
use App\Models\Microsite;
use App\Models\Payment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;
use Tests\Traits\CreatesMicrosites;
use Tests\Traits\PlaceToPayMockTrait;

class PaymentReturnTest extends TestCase
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

    public function test_return_after_payment(): void
    {
        $this->fakeCheckApprovedPayment();

        $paymentReference = 'test_reference';

        Payment::factory()->create([
            'reference' => $paymentReference,
            'request_id' => 'test_request_id',
        ]);

        $response = $this->get(route('payments.return', $paymentReference));

        $response->assertOk();
        $response->assertInertia(
            fn (Assert $page) => $page
                ->component('Payments/Return')
                ->has('payment')
                ->has('customerName')
                ->has('micrositeName')
        );
    }

    public function test_return_after_payment_error(): void
    {
        $this->fakeCheckFailedPayment();

        $paymentReference = 'test_reference';
        Payment::factory()->create([
            'reference' => $paymentReference,
            'request_id' => 'test_request_id',
            'microsite_id' => $this->basicMicrosite->id,
        ]);

        $response = $this->get(route('payments.return', $paymentReference));

        $response->assertRedirect(route('payments.show', $this->basicMicrosite));
        $response->assertSessionHasErrors();
    }

    public function test_rejected_payment(): void
    {
        $this->fakeCheckRejectedPayment();

        $paymentReference = 'test_reference';
        $payment = Payment::factory()->create([
            'reference' => $paymentReference,
            'request_id' => 'test_request_id',
        ]);

        $this->get(route('payments.return', $paymentReference));

        $this->assertDatabaseHas('payments', [
            'id' => $payment->id,
            'status' => PaymentStatus::REJECTED->value,
        ]);
    }

    public function test_already_approved_payment(): void
    {
        $this->fakeCheckApprovedPayment();

        $paymentReference = 'test_reference';
        $payment = Payment::factory()->create([
            'reference' => $paymentReference,
            'request_id' => 'test_request_id',
            'status' => PaymentStatus::APPROVED->value,
        ]);

        $this->get(route('payments.return', $paymentReference));

        $this->assertDatabaseHas('payments', [
            'id' => $payment->id,
            'status' => PaymentStatus::APPROVED->value,
        ]);
    }
}

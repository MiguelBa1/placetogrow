<?php

namespace Tests\Feature\Controllers\Payment;

use App\Constants\MicrositeType;
use App\Constants\PaymentStatus;
use App\Models\Microsite;
use App\Models\Payment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Inertia\Testing\AssertableInertia as Assert;
use Mockery;
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

        $this->basicMicrosite = $this->createMicrositeWithFields(MicrositeType::BASIC);
    }

    public function test_return_after_payment(): void
    {
        $this->fakePaymentCheckApproved();

        $paymentReference = 'test_reference';

        $payment = Payment::factory()->create([
            'reference' => $paymentReference,
            'request_id' => 'test_request_id',
        ]);

        Cache::spy();

        $response = $this->get(route('payments.return', $paymentReference));

        $response->assertOk();
        $response->assertInertia(
            fn (Assert $page) => $page
                ->component('Payments/Return')
                ->has('payment')
                ->has('customer')
                ->has('micrositeName')
        );

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
            'microsite_id' => $this->basicMicrosite->id,
        ]);

        $response = $this->get(route('payments.return', $paymentReference));

        $response->assertRedirect(route('payments.show', $this->basicMicrosite));
        $response->assertSessionHasErrors();
    }

    public function test_rejected_payment(): void
    {
        $this->fakePaymentCheckRejected();

        $paymentReference = 'test_reference';
        $payment = Payment::factory()->create([
            'reference' => $paymentReference,
            'request_id' => 'test_request_id',
        ]);

        Cache::spy();

        $this->get(route('payments.return', $paymentReference));

        $this->assertDatabaseHas('payments', [
            'id' => $payment->id,
            'status' => PaymentStatus::REJECTED->value,
        ]);

        Cache::shouldHaveReceived('put')
            ->once()
            ->with('payment_checked_' . $payment->id, PaymentStatus::REJECTED->value, Mockery::any());
    }

    public function test_already_approved_payment(): void
    {
        $this->fakePaymentCheckApproved();

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

    public function test_return_after_payment_using_cache(): void
    {
        $paymentReference = 'test_reference';
        $cachedStatus = PaymentStatus::PENDING->value;

        /** @var Payment $payment */
        $payment = Payment::factory()->create([
            'reference' => $paymentReference,
            'request_id' => 'test_request_id',
            'status' => PaymentStatus::PENDING->value,
        ]);

        Cache::shouldReceive('get')
            ->once()
            ->with('payment_checked_' . $payment->id)
            ->andReturn($cachedStatus);

        $response = $this->get(route('payments.return', $paymentReference));

        $response->assertOk();
        $response->assertInertia(
            fn (Assert $page) => $page
                ->component('Payments/Return')
                ->has('payment')
                ->has('customer')
                ->has('micrositeName')
        );

        $this->assertEquals($cachedStatus, $payment->status->value);
    }
}

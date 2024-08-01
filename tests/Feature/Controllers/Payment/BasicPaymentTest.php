<?php

namespace Tests\Feature\Controllers\Payment;

use App\Constants\DocumentType;
use App\Constants\MicrositeType;
use App\Constants\PaymentStatus;
use App\Models\Microsite;
use App\Models\Payment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;
use Tests\Traits\CreatesMicrosites;

class BasicPaymentTest extends TestCase
{
    use RefreshDatabase, CreatesMicrosites;

    private Microsite $basicMicrosite;

    public function setUp(): void
    {
        parent::setUp();

        Storage::fake('microsites_logos');
        Storage::fake('category_icons');

        $this->basicMicrosite = $this->createMicrositeWithFields(MicrositeType::BASIC);
    }

    public function test_guest_can_view_payment_page(): void
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
        $this->assertDatabaseHas('guests', [
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
        $paymentReference = 'test_reference';

        Http::fake([
            config('placetopay.url') . '/*' => Http::response([
                'requestId' => $paymentReference,
                'status' => [
                    'status' => PaymentStatus::ERROR->value,
                    'message' => 'An error occurred while processing the payment',
                ],
            ], 500)
        ]);

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

    public function test_return_after_payment(): void
    {
        $paymentReference = 'test_reference';

        Payment::factory()->create([
            'reference' => $paymentReference,
            'request_id' => 'test_request_id',
        ]);

        Http::fake([
            config('placetopay.url') . '/*' => Http::response([
                'status' => [
                    'status' => PaymentStatus::APPROVED->value,
                    'message' => 'Payment approved',
                    'date' => now()->toIso8601String(),
                ],
                'payment' => [
                    [
                        'paymentMethodName' => 'Visa Credit Card',
                        'authorization' => '123456',
                        'status' => [
                            'date' => now()->toIso8601String(),
                            'message' => 'Payment successful',
                            'status' => PaymentStatus::APPROVED->value,
                        ],
                    ],
                ],
            ])
        ]);

        $response = $this->get(route('payments.return', $paymentReference));

        $response->assertOk();
        $response->assertInertia(
            fn (Assert $page) => $page
            ->component('Payments/Return')
        );
    }

    public function test_return_after_payment_error(): void
    {
        $paymentReference = 'test_reference';
        Payment::factory()->create([
            'reference' => $paymentReference,
            'request_id' => 'test_request_id',
            'microsite_id' => $this->basicMicrosite->id,
        ]);

        Http::fake([
            config('placetopay.url') . '/*' => Http::response([
                'status' => [
                    'status' => PaymentStatus::ERROR->value,
                    'message' => 'An error occurred while completing the payment',
                ],
            ], 500)
        ]);

        $response = $this->get(route('payments.return', $paymentReference));

        $response->assertRedirect(route('payments.show', $this->basicMicrosite));
        $response->assertSessionHasErrors();
    }

    public function test_payment_not_approved(): void
    {
        $paymentReference = 'test_reference';
        $payment = Payment::factory()->create([
            'reference' => $paymentReference,
            'request_id' => 'test_request_id',
        ]);

        Http::fake([
            config('placetopay.url') . '/*' => Http::response([
                'requestId' => 'test_request_id',
                'status' => [
                    'status' => PaymentStatus::REJECTED->value,
                    'message' => 'Payment rejected',
                    'date' => now()->toIso8601String(),
                ],
            ])
        ]);

        $this->get(route('payments.return', $paymentReference));

        $this->assertDatabaseHas('payments', [
            'id' => $payment->id,
            'status' => PaymentStatus::REJECTED->value,
        ]);
    }

}

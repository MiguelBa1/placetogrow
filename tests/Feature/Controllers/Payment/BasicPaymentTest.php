<?php

namespace Tests\Feature\Controllers\Payment;

use App\Constants\DocumentType;
use App\Constants\MicrositeType;
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
                    'status' => 'PENDING',
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
            'status' => 'PENDING',
            'amount' => 10000,
        ]);
    }

    public function test_store_payment_error(): void
    {
        Http::fake([
            config('placetopay.url') . '/*' => Http::response([
                'status' => [
                    'status' => 'ERROR',
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
        Payment::factory()->create([
            'payment_reference' => 'test_reference',
            'request_id' => 'test_request_id',
        ]);

        Http::fake([
            config('placetopay.url') . '/*' => Http::response([
                'status' => [
                    'status' => 'APPROVED',
                    'message' => 'Payment approved',
                    'date' => now()->toIso8601String(),
                ],
                'payment' => [
                    [
                        'internalReference' => 'internal_ref',
                        'franchise' => 'Visa',
                        'paymentMethod' => 'Credit Card',
                        'paymentMethodName' => 'Visa Credit Card',
                        'issuerName' => 'Issuer',
                        'authorization' => 'auth_code',
                        'receipt' => 'receipt_number',
                        'status' => [
                            'date' => now()->toIso8601String(),
                            'message' => 'Payment successful',
                            'status' => 'APPROVED',
                        ],
                    ],
                ],
            ])
        ]);

        $response = $this->get(route('payments.return', [
            'reference' => 'test_reference',
            'microsite' => $this->basicMicrosite->slug,
        ]));

        $response->assertOk();
        $response->assertInertia(
            fn (Assert $page) => $page
            ->component('Payments/Return')
        );
    }

    public function test_return_after_payment_error(): void
    {
        Payment::factory()->create([
            'payment_reference' => 'test_reference',
            'request_id' => 'test_request_id',
        ]);

        Http::fake([
            config('placetopay.url') . '/*' => Http::response([
                'status' => [
                    'status' => 'ERROR',
                    'message' => 'An error occurred while completing the payment',
                ],
            ], 500)
        ]);

        $response = $this->get(route('payments.return', [
            'reference' => 'test_reference',
            'microsite' => $this->basicMicrosite->slug,
        ]));

        $response->assertRedirect(route('payments.show', $this->basicMicrosite));
        $response->assertSessionHasErrors();
    }

    public function test_check_undefined_payment(): void
    {
        $response = $this->get(route('payments.return', [
            'reference' => 'test_reference',
            'microsite' => $this->basicMicrosite->slug,
        ]));

        $response->assertRedirect(route('payments.show', $this->basicMicrosite));
        $response->assertSessionHasErrors();
    }

    public function test_payment_not_approved(): void
    {
        $payment = Payment::factory()->create([
            'payment_reference' => 'test_reference',
            'request_id' => 'test_request_id',
        ]);

        Http::fake([
            config('placetopay.url') . '/*' => Http::response([
                'status' => [
                    'status' => 'REJECTED',
                    'message' => 'Payment rejected',
                    'date' => now()->toIso8601String(),
                ],
            ])
        ]);

        $this->get(route('payments.return', [
            'microsite' => $this->basicMicrosite->slug,
            'reference' => 'test_reference',
        ]));

        $this->assertDatabaseHas('payments', [
            'id' => $payment->id,
            'status' => 'REJECTED',
        ]);
    }

}
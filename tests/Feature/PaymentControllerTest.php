<?php

namespace Tests\Feature;

use App\Models\Payment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class PaymentControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_store_payment(): void
    {
        Http::fake([
            env('P2P_URL') . '/*' => Http::response([
                'processUrl' => '/success',
                'requestId' => 'test_request_id',
                'status' => [
                    'status' => 'PENDING',
                    'message' => 'Payment pending',
                    'date' => now()->toIso8601String(),
                ],
            ])
        ]);

        $response = $this->post(route('payment.store'), [
            'name' => 'John',
            'lastName' => 'Doe',
            'email' => 'john@example.com',
            'documentNumber' => '123456789',
            'documentType' => 'CC',
            'phone' => '3001234567',
            'currency' => 'COP',
            'amount' => 10000,
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
            'currency' => 'COP',
            'amount' => 10000,
        ]);
    }

    public function test_store_payment_error(): void
    {
        Http::fake([
            env('P2P_URL') . '/*' => Http::response([
                'status' => [
                    'status' => 'ERROR',
                    'message' => 'An error occurred while processing the payment',
                ],
            ], 500)
        ]);

        $response = $this->post(route('payment.store'), [
            'name' => 'John',
            'lastName' => 'Doe',
            'email' => 'john@example.com',
            'documentNumber' => '123456789',
            'documentType' => 'CC',
            'phone' => '3001234567',
            'currency' => 'COP',
            'amount' => 10000,
        ]);

        $response->assertRedirect(route('site1'));
        $response->assertSessionHasErrors();
    }

    public function test_return_after_payment(): void
    {
        Payment::factory()->create([
            'payment_reference' => 'test_reference',
            'request_id' => 'test_request_id',
        ]);

        Http::fake([
            env('P2P_URL') . '/*' => Http::response([
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

        $response = $this->get(route('site1.return', ['reference' => 'test_reference']));

        $response->assertOk();
        $response->assertInertia(
            fn (Assert $page) => $page
            ->component('Site1/Return')
        );
    }

    public function test_return_after_payment_error(): void
    {
        Payment::factory()->create([
            'payment_reference' => 'test_reference',
            'request_id' => 'test_request_id',
        ]);

        Http::fake([
            env('P2P_URL') . '/*' => Http::response([
                'status' => [
                    'status' => 'ERROR',
                    'message' => 'An error occurred while completing the payment',
                ],
            ], 500)
        ]);

        $response = $this->get(route('site1.return', ['reference' => 'test_reference']));

        $response->assertRedirect(route('site1'));
        $response->assertSessionHasErrors();
    }

    public function test_check_undefined_payment(): void
    {
        $response = $this->get(route('site1.return', ['reference' => 'test_reference']));

        $response->assertRedirect('/site1');
        $response->assertSessionHasErrors();
    }

    public function test_payment_not_approved(): void
    {
        $payment = Payment::factory()->create([
            'payment_reference' => 'test_reference',
            'request_id' => 'test_request_id',
        ]);

        Http::fake([
            env('P2P_URL') . '/*' => Http::response([
                'status' => [
                    'status' => 'REJECTED',
                    'message' => 'Payment rejected',
                    'date' => now()->toIso8601String(),
                ],
            ])
        ]);

        $this->get(route('site1.return', ['reference' => 'test_reference']));

        $this->assertDatabaseHas('payments', [
            'id' => $payment->id,
            'status' => 'REJECTED',
        ]);
    }

}

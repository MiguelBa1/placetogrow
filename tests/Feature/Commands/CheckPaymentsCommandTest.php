<?php

namespace Feature\Commands;

use App\Models\Guest;
use App\Models\Payment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class CheckPaymentsCommandTest extends TestCase
{
    use RefreshDatabase;

    public function testCheckPaymentsCommand()
    {
        $guest = Guest::factory()->create();
        $payment = Payment::factory()->create([
            'guest_id' => $guest->id,
            'payment_reference' => 'test_reference',
            'request_id' => 'test_request_id',
            'status' => 'PENDING',
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
                        'paymentMethod' => 'Credit Card',
                        'paymentMethodName' => 'Visa Credit Card',
                        'issuerName' => 'Issuer',
                        'authorization' => 'auth_code',
                        'status' => [
                            'date' => now()->toIso8601String(),
                            'message' => 'Payment successful',
                            'status' => 'APPROVED',
                        ],
                    ],
                ],
            ])
        ]);

        Artisan::call('app:check-payments');

        $payment->refresh();

        $this->assertEquals('APPROVED', $payment->status);
        $this->assertEquals('Visa Credit Card', $payment->payment_method_name);
        $this->assertEquals('auth_code', $payment->authorization);
        $this->assertNotNull($payment->payment_date);
    }
}

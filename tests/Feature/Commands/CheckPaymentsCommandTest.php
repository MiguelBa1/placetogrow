<?php

namespace Feature\Commands;

use App\Constants\PaymentStatus;
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
            'reference' => 'test_reference',
            'request_id' => 'test_request_id',
            'status' => PaymentStatus::PENDING->value,
        ]);

        Http::fake([
            env('P2P_URL') . '/*' => Http::response([
                'status' => [
                    'status' => PaymentStatus::APPROVED->value,
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
                            'status' => PaymentStatus::APPROVED->value,
                        ],
                    ],
                ],
            ])
        ]);

        Artisan::call('app:check-payments');

        $payment->refresh();

        $this->assertEquals(PaymentStatus::APPROVED->value, $payment->status);
        $this->assertEquals('Visa Credit Card', $payment->payment_method_name);
        $this->assertEquals('auth_code', $payment->authorization);
        $this->assertNotNull($payment->payment_date);
    }
}

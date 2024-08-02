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
            'created_at' => now()->subMinutes(config('payments.check_interval_minutes'))->subSecond(),
        ]);

        Http::fake([
            config('payments.placetopay.url') . '/*' => Http::response([
                'status' => [
                    'status' => PaymentStatus::APPROVED->value,
                    'message' => 'Payment approved',
                    'date' => now()->toIso8601String(),
                ],
                'payment' => [
                    [
                        'paymentMethodName' => 'Visa Credit Card',
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

    public function testOnlyCheckPendingPaymentsAfterInterval()
    {
        $guest = Guest::factory()->create();
        $payment = Payment::factory()->create([
            'guest_id' => $guest->id,
            'reference' => 'test_reference',
            'request_id' => 'test_request_id',
            'status' => PaymentStatus::PENDING->value,
            'created_at' => now()->subMinutes(config('payments.check_interval_minutes'))->subSecond(),
            'payment_date' => null,
        ]);

        $payment2 = Payment::factory()->create([
            'guest_id' => $guest->id,
            'reference' => 'test_reference2',
            'request_id' => 'test_request_id2',
            'status' => PaymentStatus::PENDING->value,
            'created_at' => now()->subMinutes(config('payments.check_interval_minutes'))->addSecond(),
            'payment_date' => null,
        ]);

        Http::fake([
             config('payments.placetopay.url') . '/*' => Http::response([
                'status' => [
                    'status' => PaymentStatus::APPROVED->value,
                    'message' => 'Payment approved',
                    'date' => now()->toIso8601String(),
                ],
                'payment' => [
                    [
                        'paymentMethodName' => 'Visa Credit Card',
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
        $payment2->refresh();

        $this->assertEquals(PaymentStatus::APPROVED->value, $payment->status);
        $this->assertEquals('Visa Credit Card', $payment->payment_method_name);
        $this->assertEquals('auth_code', $payment->authorization);
        $this->assertNotNull($payment->payment_date);

        $this->assertEquals(PaymentStatus::PENDING->value, $payment2->status);
        $this->assertNull($payment2->payment_date);
    }
}

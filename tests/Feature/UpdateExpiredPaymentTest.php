<?php

namespace Tests\Feature;

use App\Constants\PaymentStatus;
use App\Models\Payment;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class UpdateExpiredPaymentTest extends TestCase
{
    use RefreshDatabase;

    public function test_updates_expired_payments_status(): void
    {
        $paymentA = Payment::factory()->create([
            'reference' => 'ref1',
            'payment_date' => Carbon::now()->subDays()->toIso8601String(),
            'status' => PaymentStatus::PENDING->value,
        ]);

        $paymentB = Payment::factory()->create(['reference' => 'ref2','status' => PaymentStatus::APPROVED->value]);
        $paymentC = Payment::factory()->create(['reference' => 'ref3','status' => PaymentStatus::PENDING->value]);

        Artisan::call('payments:update-expired');

        $paymentA->refresh();
        $this->assertEquals('EXPIRED', $paymentA->status->value);
        $this->assertEquals('APPROVED', $paymentB->status->value);
        $this->assertEquals('PENDING', $paymentC->status->value);

        $this->assertDatabaseHas(
            'payments',
            [
                'reference' => 'ref1',
                'status' => 'EXPIRED'
            ]
        );
    }
}

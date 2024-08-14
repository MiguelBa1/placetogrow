<?php

namespace Tests\Feature;

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
            'payment_reference' => 'ref1',
            'expires_in' => Carbon::now()->subDays(),
            'status' => 'ACTIVE'
        ]);

        $paymentB = Payment::factory()->create(['payment_reference' => 'ref2','status' => 'APPROVED']);
        $paymentC = Payment::factory()->create(['payment_reference' => 'ref3','status' => 'PENDING']);

        Artisan::call('payments:update-expired');

        $paymentA->refresh();
        $this->assertEquals('EXPIRED', $paymentA->status);
        $this->assertEquals('APPROVED', $paymentB->status);
        $this->assertEquals('PENDING', $paymentC->status);

        $this->assertDatabaseHas(
            'payments',
            [
                'payment_reference' => 'ref1',
                'status' => 'EXPIRED'
            ]
        );
    }
}

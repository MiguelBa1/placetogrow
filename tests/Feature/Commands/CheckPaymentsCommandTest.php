<?php

namespace Tests\Feature\Commands;

use App\Constants\PaymentStatus;
use App\Models\Customer;
use App\Models\Payment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;
use Tests\Traits\PlaceToPayMockTrait;

class CheckPaymentsCommandTest extends TestCase
{
    use RefreshDatabase, PlaceToPayMockTrait;

    public function test_can_check_payments()
    {
        $this->fakePaymentCheckApproved();

        $customer = Customer::factory()->create();
        $payment = Payment::factory()->create([
            'customer_id' => $customer->id,
            'reference' => 'test_reference',
            'request_id' => 'test_request_id',
            'status' => PaymentStatus::PENDING->value,
            'created_at' => now()->subMinutes(config('payments.check_interval_minutes'))->subSecond(),
        ]);

        Cache::shouldReceive('forget')->once()->with('payment_status_' . $payment->id);

        Artisan::call('app:check-payments');

        $payment->refresh();

        $this->assertEquals(PaymentStatus::APPROVED->value, $payment->status->value);
        $this->assertEquals('Visa Credit Card', $payment->payment_method_name);
        $this->assertEquals('auth_code', $payment->authorization);
        $this->assertNotNull($payment->payment_date);
    }

    public function test_only_check_pending_payments_after_interval()
    {
        $this->fakePaymentCheckApproved();

        $customer = Customer::factory()->create();
        $payment = Payment::factory()->create([
            'customer_id' => $customer->id,
            'reference' => 'test_reference',
            'request_id' => 'test_request_id',
            'status' => PaymentStatus::PENDING->value,
            'created_at' => now()->subMinutes(config('payments.check_interval_minutes'))->subSecond(),
            'payment_date' => null,
        ]);

        $payment2 = Payment::factory()->create([
            'customer_id' => $customer->id,
            'reference' => 'test_reference2',
            'request_id' => 'test_request_id2',
            'status' => PaymentStatus::PENDING->value,
            'created_at' => now()->subMinutes(config('payments.check_interval_minutes'))->addSecond(),
            'payment_date' => null,
        ]);

        Cache::shouldReceive('forget')->once()->with('payment_status_' . $payment->id);

        Artisan::call('app:check-payments');

        $payment->refresh();
        $payment2->refresh();

        $this->assertEquals(PaymentStatus::APPROVED->value, $payment->status->value);
        $this->assertEquals('Visa Credit Card', $payment->payment_method_name);
        $this->assertEquals('auth_code', $payment->authorization);
        $this->assertNotNull($payment->payment_date);

        $this->assertEquals(PaymentStatus::PENDING->value, $payment2->status->value);
        $this->assertNull($payment2->payment_date);
    }
}

<?php

namespace Tests\Feature\Jobs;

use App\Constants\MicrositeType;
use App\Constants\PaymentStatus;
use App\Jobs\CheckPaymentStatusJob;
use App\Models\Customer;
use App\Models\Microsite;
use App\Models\Payment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;
use Tests\Traits\PlaceToPayMockTrait;

class CheckPaymentStatusJobTest extends TestCase
{
    use RefreshDatabase, PlaceToPayMockTrait;

    public function test_job_processes_payment_successfully()
    {
        $this->fakePaymentCheckApproved();
        Cache::spy();

        $customer = Customer::factory()->create();

        $microsite = Microsite::factory()->create([
            'type' => MicrositeType::BASIC->value,
        ]);

        $payment = Payment::factory()->create([
            'microsite_id' => $microsite->id,
            'customer_id' => $customer->id,
            'reference' => 'test_reference',
            'request_id' => 'test_request_id',
            'status' => PaymentStatus::PENDING->value,
            'created_at' => now()->subMinutes(config('payments.check_interval_minutes'))->subSecond(),
        ]);

        $job = new CheckPaymentStatusJob($payment);
        $job->withFakeQueueInteractions();
        $job->handle();

        Cache::shouldHaveReceived('forget')->once()->with('payment_checked_' . $payment->id);
        $payment->refresh();

        $this->assertEquals(PaymentStatus::APPROVED->value, $payment->status->value);
        $this->assertEquals('Visa Credit Card', $payment->payment_method_name);
        $this->assertEquals('auth_code', $payment->authorization);
        $this->assertNotNull($payment->payment_date);
    }
}

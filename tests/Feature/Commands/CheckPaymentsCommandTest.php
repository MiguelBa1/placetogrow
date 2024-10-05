<?php

namespace Tests\Feature\Commands;

use App\Constants\PaymentStatus;
use App\Jobs\CheckPaymentStatusJob;
use App\Models\Customer;
use App\Models\Payment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Bus;
use Tests\TestCase;

class CheckPaymentsCommandTest extends TestCase
{
    use RefreshDatabase;

    public function test_command_dispatches_jobs_for_due_payments()
    {
        $customer = Customer::factory()->create();
        $duePayment = Payment::factory()->create([
            'customer_id' => $customer->id,
            'reference' => 'due_reference',
            'request_id' => 'due_request_id',
            'status' => PaymentStatus::PENDING->value,
            'created_at' => now()->subMinutes(config('payments.check_interval_minutes'))->subSecond(),
        ]);

        $nonDuePayment = Payment::factory()->create([
            'customer_id' => $customer->id,
            'reference' => 'non_due_reference',
            'request_id' => 'non_due_request_id',
            'status' => PaymentStatus::PENDING->value,
            'created_at' => now()->subMinutes(config('payments.check_interval_minutes'))->addSecond(),
        ]);

        Bus::fake();

        Artisan::call('app:check-payments');

        Bus::assertDispatched(CheckPaymentStatusJob::class, function ($job) use ($duePayment) {
            return $job->payment->id === $duePayment->id;
        });

        Bus::assertNotDispatched(CheckPaymentStatusJob::class, function ($job) use ($nonDuePayment) {
            return $job->payment->id === $nonDuePayment->id;
        });
    }

    public function test_command_does_not_dispatch_jobs_for_payments_not_due()
    {
        Bus::fake();

        $this->artisan('app:check-payments')
            ->expectsOutput('No payments to check')
            ->assertExitCode(0);

        Bus::assertNotDispatched(CheckPaymentStatusJob::class);
    }
}

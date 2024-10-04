<?php

namespace App\Console\Commands;

use App\Constants\PaymentStatus;
use App\Jobs\CheckPaymentStatusJob;
use App\Models\Payment;
use Illuminate\Console\Command;

class CheckPaymentsCommand extends Command
{
    protected $signature = 'app:check-payments';

    protected $description = 'Command to check the status of the payments';

    public function handle(): void
    {
        $payments = Payment::query()
            ->where('status', PaymentStatus::PENDING->value)
            ->where('created_at', '<', now()->subMinutes(config('payments.check_interval_minutes')))
            ->get();

        if ($payments->isEmpty()) {
            $this->info('No payments to check');
            return;
        }

        foreach ($payments as $payment) {
            $this->info('Dispatching job to check payment ' . $payment->request_id);
            CheckPaymentStatusJob::dispatch($payment);
        }
    }
}

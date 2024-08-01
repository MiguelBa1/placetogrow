<?php

namespace App\Console\Commands;

use App\Constants\PaymentStatus;
use App\Contracts\PaymentServiceInterface;
use App\Models\Payment;
use Illuminate\Console\Command;

class CheckPaymentsCommand extends Command
{

    private PaymentServiceInterface $paymentService;

    public function __construct()
    {
        parent::__construct();
        $this->paymentService = app(PaymentServiceInterface::class);
    }

    protected $signature = 'app:check-payments';

    protected $description = 'Command to check the status of the payments';

    public function handle(): void
    {
        $payments = Payment::query()->where('status', PaymentStatus::PENDING->value)
            ->where('created_at', '<', config('placetopay.expiration'))
            ->get();

        foreach ($payments as $payment) {
            $this->info('Checking payment ' . $payment->request_id);
            $this->paymentService->checkPayment($payment);
        }
    }
}

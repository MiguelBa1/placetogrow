<?php

namespace App\Jobs;

use App\Contracts\PaymentServiceInterface;
use App\Models\Payment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;

class CheckPaymentStatusJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public Payment $payment;
    private PaymentServiceInterface $paymentService;

    public function __construct(Payment $payment)
    {
        $this->payment = $payment;
        $this->paymentService = app(PaymentServiceInterface::class);
    }

    public function handle(): void
    {
        $isSuccessful = $this->paymentService->checkPayment($this->payment);

        if ($isSuccessful) {
            Cache::forget('payment_checked_' . $this->payment->id);
        }
    }
}

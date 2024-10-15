<?php

namespace App\Jobs;

use App\Constants\InvoiceStatus;
use App\Constants\LateFeeType;
use App\Models\Invoice;
use App\Models\Microsite;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CalculateLateFeesJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected Microsite $microsite;

    public function __construct(Microsite $microsite)
    {
        $this->microsite = $microsite;
    }

    public function handle(): void
    {
        $invoices = Invoice::where('microsite_id', $this->microsite->id)
            ->where('status', InvoiceStatus::PENDING)
            ->get();

        foreach ($invoices as $invoice) {
            $this->applyLateFee($invoice);
        }
    }

    protected function applyLateFee(Invoice $invoice): void
    {
        $lateFeeSettings = $this->microsite->settings['late_fee'] ?? null;

        if (!$lateFeeSettings) {
            $invoice->late_fee = 0;
            $invoice->total_amount = $invoice->amount;
        } else {
            $lateFee = 0;

            if ($lateFeeSettings['type'] === LateFeeType::FIXED->value) {
                $lateFee = $lateFeeSettings['value'];
            } elseif ($lateFeeSettings['type'] === LateFeeType::PERCENTAGE->value) {
                $lateFee = ($invoice->amount * $lateFeeSettings['value']) / 100;
            }

            $invoice->late_fee = $lateFee;
            $invoice->total_amount = $invoice->amount + $lateFee;
        }

        $invoice->save();
    }
}

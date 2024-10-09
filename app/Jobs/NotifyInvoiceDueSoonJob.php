<?php

namespace App\Jobs;

use App\Constants\InvoiceStatus;
use App\Mail\InvoiceDueSoonMail;
use App\Models\Invoice;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class NotifyInvoiceDueSoonJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle(): void
    {
        $daysBeforeDue = (int) config('invoices.notification.days_before_due');
        $today = Carbon::today();

        $invoices = Invoice::select(['id', 'microsite_id', 'name', 'email', 'amount', 'expiration_date', 'reference'])
            ->whereDate('expiration_date', '=', $today->addDays($daysBeforeDue))
            ->where('status', InvoiceStatus::PENDING->value)
            ->with(['microsite:id,name'])
            ->get();

        foreach ($invoices as $invoice) {
            Mail::to($invoice->email)->send(new InvoiceDueSoonMail($invoice));
        }
    }
}

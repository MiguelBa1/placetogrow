<?php

namespace Tests\Feature\Jobs;

use App\Constants\InvoiceStatus;
use App\Jobs\NotifyInvoiceDueSoonJob;
use App\Mail\InvoiceDueSoonMail;
use App\Models\Invoice;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class NotifyInvoiceDueSoonJobTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_sends_email_for_invoices_due_soon(): void
    {
        Mail::fake();

        $daysBeforeDue = 5;
        config()->set('invoices.notification.days_before_due', $daysBeforeDue);

        $invoice = Invoice::factory()->create([
            'status' => InvoiceStatus::PENDING->value,
            'expiration_date' => Carbon::today()->addDays($daysBeforeDue),
        ]);

        (new NotifyInvoiceDueSoonJob())->handle();

        Mail::assertQueued(InvoiceDueSoonMail::class, function ($mail) use ($invoice) {
            return $mail->hasTo($invoice->email) &&
                $mail->invoice->is($invoice);
        });
    }

    public function test_it_does_not_send_email_for_paid_invoices(): void
    {
        Mail::fake();

        $daysBeforeDue = 5;
        config()->set('invoices.notification.days_before_due', $daysBeforeDue);

        Invoice::factory()->create([
            'status' => InvoiceStatus::PAID->value,
            'expiration_date' => Carbon::today()->addDays($daysBeforeDue),
        ]);

        (new NotifyInvoiceDueSoonJob())->handle();

        Mail::assertNothingSent();
    }

    public function test_it_does_not_send_email_if_invoice_not_due_soon(): void
    {
        Mail::fake();

        $daysBeforeDue = 5;
        config()->set('invoices.notification.days_before_due', $daysBeforeDue);

        Invoice::factory()->create([
            'status' => InvoiceStatus::PENDING->value,
            'expiration_date' => Carbon::today()->addDays($daysBeforeDue + 1),
        ]);

        (new NotifyInvoiceDueSoonJob())->handle();

        Mail::assertNothingSent();
    }
}

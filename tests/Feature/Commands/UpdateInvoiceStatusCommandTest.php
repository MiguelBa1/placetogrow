<?php

namespace Tests\Feature\Commands;

use App\Constants\InvoiceStatus;
use App\Models\Invoice;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UpdateInvoiceStatusCommandTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_update_invoice_status()
    {
        $invoice = Invoice::factory()->create([
            'expiration_date' => now()->subDay(),
            'status' => InvoiceStatus::PENDING,
        ]);

        $this->artisan('invoice:update-status')
            ->expectsOutput('Updated 1 invoices to expired status.')
            ->assertExitCode(0);

        $invoice->refresh();

        $this->assertEquals(InvoiceStatus::EXPIRED, $invoice->status);
    }
}

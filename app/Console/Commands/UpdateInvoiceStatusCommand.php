<?php

namespace App\Console\Commands;

use App\Constants\InvoiceStatus;
use App\Models\Invoice;
use Carbon\Carbon;
use Illuminate\Console\Command;

class UpdateInvoiceStatusCommand extends Command
{
    protected $signature = 'invoice:update-status';

    protected $description = 'Update invoice status based on expiration date';

    public function handle(): int
    {
        $now = Carbon::now();

        $updated = Invoice::where('expiration_date', '<', $now)
            ->where('status', '!=', InvoiceStatus::EXPIRED->value)
            ->update(['status' => InvoiceStatus::EXPIRED]);

        $this->info("Updated $updated invoices to expired status.");

        return 0;
    }
}

<?php

namespace App\Console\Commands;

use App\Constants\InvoiceStatus;
use App\Models\Invoice;
use Carbon\Carbon;
use Illuminate\Console\Command;

class UpdateInvoiceStatusCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'invoice:update-status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update invoice status based on expiration date';

    /**
     * Execute the console command.
     *
     * @return int
     */
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

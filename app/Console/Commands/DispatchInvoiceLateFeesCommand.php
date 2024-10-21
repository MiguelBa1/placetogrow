<?php

namespace App\Console\Commands;

use App\Constants\MicrositeType;
use App\Jobs\CalculateLateFeesJob;
use App\Models\Microsite;
use Illuminate\Console\Command;

class DispatchInvoiceLateFeesCommand extends Command
{
    protected $signature = 'app:dispatch-invoice-late-fees-command';

    protected $description = 'Calculate and apply late fees to pending invoices';

    public function handle()
    {
        $microsites = Microsite::where('type', MicrositeType::INVOICE->value)->get();

        if ($microsites->isEmpty()) {
            $this->info('No microsites found');
            return;
        }

        foreach ($microsites as $microsite) {
            CalculateLateFeesJob::dispatch($microsite);
            $this->info("Late fees calculation dispatched for microsite {$microsite->name}");
        }

        $this->info('Late fees calculation dispatched successfully');
    }
}

<?php

namespace Tests\Feature\Commands;

use App\Constants\MicrositeType;
use App\Jobs\CalculateLateFeesJob;
use App\Models\Microsite;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Bus;
use Tests\TestCase;

class DispatchInvoiceLateFeesCommandTest extends TestCase
{
    use RefreshDatabase;

    public function test_dispatch_invoice_late_fees_command_dispatches_jobs(): void
    {
        Bus::fake();

        $microsite1 = Microsite::factory()->create(['type' => MicrositeType::INVOICE->value]);
        $microsite2 = Microsite::factory()->create(['type' => MicrositeType::INVOICE->value]);

        $this->artisan('app:dispatch-invoice-late-fees-command')
            ->expectsOutput("Late fees calculation dispatched for microsite {$microsite1->name}")
            ->expectsOutput("Late fees calculation dispatched for microsite {$microsite2->name}")
            ->expectsOutput('Late fees calculation dispatched successfully')
            ->assertExitCode(0);

        Bus::assertDispatched(CalculateLateFeesJob::class, function ($job) use ($microsite1) {
            return $job->microsite->id === $microsite1->id;
        });

        Bus::assertDispatched(CalculateLateFeesJob::class, function ($job) use ($microsite2) {
            return $job->microsite->id === $microsite2->id;
        });
    }

    public function test_dispatch_invoice_late_fees_command_handles_no_microsites(): void
    {
        Bus::fake();

        $this->artisan('app:dispatch-invoice-late-fees-command')
            ->expectsOutput('No microsites found')
            ->assertExitCode(0);

        Bus::assertNotDispatched(CalculateLateFeesJob::class);
    }
}

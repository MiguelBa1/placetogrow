<?php

namespace Tests\Feature\Jobs;

use App\Constants\InvoiceStatus;
use App\Constants\LateFeeType;
use App\Jobs\CalculateLateFeesJob;
use App\Models\Invoice;
use App\Models\Microsite;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CalculateLateFeesJobTest extends TestCase
{
    use RefreshDatabase;

    public function test_calculate_late_fees_for_invoices_without_settings(): void
    {
        $microsite = Microsite::factory()->create([
            'settings' => []
        ]);

        $invoice = Invoice::factory()->create([
            'microsite_id' => $microsite->id,
            'status' => InvoiceStatus::PENDING->value,
            'amount' => 1000,
        ]);

        $job = new CalculateLateFeesJob($microsite);
        $job->withFakeQueueInteractions();
        $job->handle();

        $invoice->refresh();

        $this->assertEquals(0, $invoice->late_fee);
        $this->assertEquals(1000, $invoice->total_amount);
    }

    public function test_calculate_late_fees_with_fixed_late_fee(): void
    {
        $microsite = Microsite::factory()->create([
            'settings' => [
                'late_fee' => [
                    'type' => LateFeeType::FIXED->value,
                    'value' => 50,
                ],
            ],
        ]);

        $invoice = Invoice::factory()->create([
            'microsite_id' => $microsite->id,
            'status' => InvoiceStatus::PENDING->value,
            'amount' => 1000,
        ]);

        $job = new CalculateLateFeesJob($microsite);
        $job->withFakeQueueInteractions();
        $job->handle();

        $invoice->refresh();

        $this->assertEquals(50, $invoice->late_fee);
        $this->assertEquals(1050, $invoice->total_amount);
    }

    public function test_calculate_late_fees_with_percentage_late_fee(): void
    {
        $microsite = Microsite::factory()->create([
            'settings' => [
                'late_fee' => [
                    'type' => LateFeeType::PERCENTAGE->value,
                    'value' => 10,
                ],
            ],
        ]);

        $invoice = Invoice::factory()->create([
            'microsite_id' => $microsite->id,
            'status' => InvoiceStatus::PENDING->value,
            'amount' => 1000,
        ]);

        $job = new CalculateLateFeesJob($microsite);
        $job->withFakeQueueInteractions();
        $job->handle();

        $invoice->refresh();

        $this->assertEquals(100, $invoice->late_fee);
        $this->assertEquals(1100, $invoice->total_amount);
    }
}

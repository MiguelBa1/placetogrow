<?php

namespace Database\Seeders;

use App\Actions\MicrositeField\AttachMicrositeFieldsAction;
use App\Constants\MicrositeType;
use App\Models\Category;
use App\Models\Invoice;
use App\Models\Microsite;
use App\Models\Payment;
use App\Models\Plan;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class CategoryMicrositeSeeder extends Seeder
{
    protected AttachMicrositeFieldsAction $attachMicrositeFieldsAction;

    public function __construct(AttachMicrositeFieldsAction $attachMicrositeFieldsAction)
    {
        $this->attachMicrositeFieldsAction = $attachMicrositeFieldsAction;
    }

    public function run(int $count): void
    {
        $this->cleanDirectories();

        $categories = Category::factory(5)->create();

        $microsites = Microsite::factory($count)
            ->recycle($categories)
            ->create();

        /** @var Microsite $microsite */
        foreach ($microsites as $microsite) {
            $this->attachMicrositeFieldsAction->execute($microsite);

            if ($microsite->type->value === MicrositeType::INVOICE->value) {
                Invoice::factory(3)
                    ->pending()
                    ->recycle($microsite)
                    ->create();

                Payment::factory(2)
                    ->withInvoice()
                    ->recycle($microsite)
                    ->create();

                $expiredInvoices = Invoice::factory(2)
                    ->expired()
                    ->recycle($microsite)
                    ->create();

                Payment::factory(1)
                    ->recycle($microsite)
                    ->recycle($expiredInvoices)
                    ->withInvoice()
                    ->create();
            }

            if ($microsite->type->value === MicrositeType::SUBSCRIPTION->value) {
                $plans = Plan::factory(2)
                    ->recycle($microsite)
                    ->create();

                Payment::factory(3)
                    ->recycle($plans)
                    ->recycle($microsite)
                    ->withPlan()
                    ->create();
            }
            if ($microsite->type->value === MicrositeType::BASIC->value) {
                Payment::factory(3)
                    ->recycle($microsite)
                    ->create();
            }
        }
    }

    private function cleanDirectories(): void
    {
        $disks = ['microsites_logos', 'category_icons'];

        foreach ($disks as $disk) {
            if (Storage::disk($disk)->exists('/')) {
                Storage::disk($disk)->deleteDirectory('/');
            }
        }
    }
}

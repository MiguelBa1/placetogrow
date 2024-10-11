<?php

namespace Database\Seeders;

use App\Actions\MicrositeField\AttachMicrositeFieldsAction;
use App\Constants\MicrositeType;
use App\Models\Category;
use App\Models\Invoice;
use App\Models\Microsite;
use App\Models\Payment;
use App\Models\Plan;
use App\Models\Subscription;
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

                $randomPendingPayments = random_int(1, 5);
                Payment::factory($randomPendingPayments)
                    ->withInvoice()
                    ->recycle($microsite)
                    ->create();

                $expiredInvoices = Invoice::factory(2)
                    ->expired()
                    ->recycle($microsite)
                    ->create();

                $randomExpiredPayments = random_int(1, 3);
                Payment::factory($randomExpiredPayments)
                    ->recycle($microsite)
                    ->recycle($expiredInvoices)
                    ->withInvoice()
                    ->create();
            }

            if ($microsite->type->value === MicrositeType::SUBSCRIPTION->value) {
                $plans = Plan::factory(2)
                    ->recycle($microsite)
                    ->create();

                $randomPlanPayments = random_int(1, 5);
                Payment::factory($randomPlanPayments)
                    ->recycle($plans)
                    ->recycle($microsite)
                    ->withPlan()
                    ->create();

                Subscription::factory(2)
                    ->recycle($plans)
                    ->create();
            }

            if ($microsite->type->value === MicrositeType::BASIC->value) {
                $randomBasicPayments = random_int(1, 4);
                Payment::factory($randomBasicPayments)
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

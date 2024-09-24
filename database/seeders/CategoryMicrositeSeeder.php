<?php

namespace Database\Seeders;

use App\Actions\MicrositeField\AttachMicrositeFieldsAction;
use App\Constants\MicrositeType;
use App\Models\Category;
use App\Models\Invoice;
use App\Models\Microsite;
use App\Models\Plan;
use App\Models\SubscriptionTranslation;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class CategoryMicrositeSeeder extends Seeder
{
    protected AttachMicrositeFieldsAction $attachMicrositeFieldsAction;

    public function __construct(AttachMicrositeFieldsAction $attachMicrositeFieldsAction)
    {
        $this->attachMicrositeFieldsAction = $attachMicrositeFieldsAction;
    }

    public function run(): void
    {
        $this->cleanDirectories();

        $categories = Category::factory()->count(5)->create();

        $microsites = Microsite::factory()->count(20)
            ->recycle($categories)
            ->create();

        foreach ($microsites as $microsite) {
            $this->attachMicrositeFieldsAction->execute($microsite);

            if ($microsite->type->value === MicrositeType::INVOICE->value) {
                Invoice::factory()->count(3)->create(['microsite_id' => $microsite->id]);
            }

            if ($microsite->type->value === MicrositeType::SUBSCRIPTION->value) {
                $this->createSubscriptionsWithTranslations($microsite);
            }
        }
    }

    private function createSubscriptionsWithTranslations(Microsite $microsite): void
    {
        $plans = Plan::factory()->count(2)->create(['microsite_id' => $microsite->id]);

        foreach ($plans as $plan) {
            foreach (['en', 'es'] as $locale) {
                SubscriptionTranslation::factory()->create([
                    'plan_id' => $plan->id,
                    'locale' => $locale,
                ]);
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

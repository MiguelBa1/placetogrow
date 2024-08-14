<?php

namespace Database\Seeders;

use App\Actions\MicrositeField\AttachMicrositeFieldsAction;
use App\Constants\MicrositeType;
use App\Models\Category;
use App\Models\Invoice;
use App\Models\Microsite;
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

        $microsites = Microsite::factory()->count(30)
            ->recycle($categories)
            ->create();

        foreach ($microsites as $microsite) {
            $this->attachMicrositeFieldsAction->execute($microsite);

            if ($microsite->type->value === MicrositeType::INVOICE->value) {
                Invoice::factory()->count(5)->create(['microsite_id' => $microsite->id]);
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

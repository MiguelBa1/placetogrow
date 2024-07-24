<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Microsite;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class CategoryMicrositeSeeder extends Seeder
{
    public function run(): void
    {
        $this->cleanDirectories();

        $categories = Category::factory()->count(5)->create();

        Microsite::factory()->count(30)
            ->recycle($categories)
            ->create();
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

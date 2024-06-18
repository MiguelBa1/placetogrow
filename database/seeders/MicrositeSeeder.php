<?php

namespace Database\Seeders;

use App\Models\Microsite;
use Illuminate\Database\Seeder;

class MicrositeSeeder extends Seeder
{
    public function run(): void
    {
        Microsite::factory()->count(10)->create();
    }
}

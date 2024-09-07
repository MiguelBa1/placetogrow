<?php

namespace Tests\Traits;

use App\Actions\MicrositeField\AttachMicrositeFieldsAction;
use App\Constants\MicrositeType;
use App\Models\Microsite;
use Illuminate\Support\Facades\Storage;

trait CreatesMicrosites
{
    protected function createMicrositeWithFields(MicrositeType $type): Microsite
    {
        Storage::fake('microsites_logos');
        Storage::fake('category_icons');

        $microsite = Microsite::factory()->create([
            'type' => $type->value,
        ]);

        (new AttachMicrositeFieldsAction)->execute($microsite);

        return $microsite;
    }
}

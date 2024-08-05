<?php

namespace Tests\Traits;

use App\Actions\MicrositeField\AttachMicrositeFieldsAction;
use App\Constants\MicrositeType;
use App\Models\Microsite;

trait CreatesMicrosites
{
    protected function createMicrositeWithFields(MicrositeType $type): Microsite
    {
        $microsite = Microsite::factory()->create([
            'type' => $type->value,
        ]);

        (new AttachMicrositeFieldsAction)->execute($microsite);

        return $microsite;
    }
}

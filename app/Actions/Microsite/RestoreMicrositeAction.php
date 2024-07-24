<?php

namespace App\Actions\Microsite;

use App\Models\Microsite;

class RestoreMicrositeAction
{
    public function execute(string $slug): void
    {
        Microsite::withTrashed()->where('slug', $slug)->firstOrFail()->restore();
    }
}

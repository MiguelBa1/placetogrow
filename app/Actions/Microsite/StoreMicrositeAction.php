<?php

namespace App\Actions\Microsite;

use App\Http\Requests\Microsite\CreateMicrositeRequest;
use App\Models\Microsite;

class StoreMicrositeAction
{
    public function execute(CreateMicrositeRequest $request): Microsite
    {
        $microsite = Microsite::create($request->except('logo'));

        if ($request->hasFile('logo')) {
            $microsite
                ->addMediaFromRequest('logo')
                ->toMediaCollection('logos');
        }

        return $microsite;
    }
}

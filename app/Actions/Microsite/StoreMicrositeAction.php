<?php

namespace App\Actions\Microsite;

use App\Actions\MicrositeField\AttachMicrositeFieldsAction;
use App\Http\Requests\Microsite\CreateMicrositeRequest;
use App\Models\Microsite;

class StoreMicrositeAction
{
    protected AttachMicrositeFieldsAction $attachMicrositeFieldsAction;

    public function __construct(AttachMicrositeFieldsAction $attachMicrositeFieldsAction)
    {
        $this->attachMicrositeFieldsAction = $attachMicrositeFieldsAction;
    }

    public function execute(CreateMicrositeRequest $request): Microsite
    {
        $microsite = Microsite::create($request->except('logo'));

        if ($request->hasFile('logo')) {
            $microsite
                ->addMediaFromRequest('logo')
                ->toMediaCollection('logos');
        }

        $this->attachMicrositeFieldsAction->execute($microsite);

        return $microsite;
    }
}

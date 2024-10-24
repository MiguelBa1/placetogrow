<?php

namespace App\Actions\Microsite;

use App\Actions\MicrositeField\AttachMicrositeFieldsAction;
use App\Constants\MicrositeType;
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
        $micrositeType = MicrositeType::from($request->validated('type'));
        $defaultSettings = $micrositeType->defaultSettings();

        $microsite = Microsite::create([
            'name' => $request->validated('name'),
            'slug' => $request->validated('slug'),
            'category_id' => $request->validated('category_id'),
            'payment_currency' => $request->validated('payment_currency'),
            'payment_expiration' => $request->validated('payment_expiration'),
            'type' => $request->validated('type'),
            'responsible_name' => $request->validated('responsible_name'),
            'responsible_document_number' => $request->validated('responsible_document_number'),
            'responsible_document_type' => $request->validated('responsible_document_type'),
            'settings' => $defaultSettings
        ]);

        if ($request->hasFile('logo')) {
            $microsite
                ->addMediaFromRequest('logo')
                ->toMediaCollection('logos');
        }

        $this->attachMicrositeFieldsAction->execute($microsite);

        return $microsite;
    }
}

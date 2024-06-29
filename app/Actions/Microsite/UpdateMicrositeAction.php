<?php

namespace App\Actions\Microsite;

use App\Http\Requests\Microsite\UpdateMicrositeRequest;
use App\Models\Microsite;
use Exception;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileDoesNotExist;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileIsTooBig;

class UpdateMicrositeAction
{
    /**
     * @throws Exception
     */
    public function execute(UpdateMicrositeRequest $request, Microsite $microsite): Microsite
    {
        $microsite->update($request->except('logo'));

        if ($request->hasFile('logo')) {
            try {
                $microsite
                    ->addMediaFromRequest('logo')
                    ->toMediaCollection('logos');
            } catch (FileDoesNotExist | FileIsTooBig $e) {
                throw new Exception(trans('messages.error.uploading_logo'));
            }
        }

        return $microsite;
    }
}

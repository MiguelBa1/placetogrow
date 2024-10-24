<?php

namespace App\Actions\Microsite;

use App\Http\Requests\Microsite\UpdateMicrositeRequest;
use App\Models\Microsite;
use Exception;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileDoesNotExist;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileIsTooBig;

class UpdateMicrositeAction
{
    public function execute(UpdateMicrositeRequest $request, Microsite $microsite): array
    {
        try {
            $microsite->update($request->except('logo'));

            if ($request->hasFile('logo')) {
                $microsite
                    ->addMediaFromRequest('logo')
                    ->toMediaCollection('logos');
            }

            return ['success' => true, 'message' => null];
        } catch (FileDoesNotExist | FileIsTooBig) {
            return ['success' => false, 'message' => trans('messages.error.uploading_logo')];
        } catch (Exception) {
            return ['success' => false, 'message' => trans('messages.error.general')];
        }
    }
}

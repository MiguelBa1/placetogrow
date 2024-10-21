<?php

namespace App\Actions\Microsite;

use App\Models\Microsite;
use Exception;

class UpdateMicrositeSettingsAction
{
    public function execute(array $settings, Microsite $microsite): array
    {
        try {
            $microsite->settings = array_merge($microsite->settings, $settings);
            $microsite->save();

            return ['success' => true, 'message' => null];
        } catch (Exception) {
            return [
                'success' => false,
                'message' => __('microsite_settings.update_error'),
            ];
        }
    }
}

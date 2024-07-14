<?php

namespace App\Http\Resources\RolePermission;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RoleResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $translationKey = "roles.{$this->name}";
        $translatedName = __($translationKey);

        // If the translation key is not found, it will return the key itself
        if ($translatedName === $translationKey) {
            $translatedName = $this->name;
        }

        return [
            'id' => $this->id,
            'name' => $translatedName,
        ];
    }
}

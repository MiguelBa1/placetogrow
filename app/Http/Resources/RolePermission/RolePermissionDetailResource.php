<?php

namespace App\Http\Resources\RolePermission;

use App\Constants\Permission;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RolePermissionDetailResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $translationKey = "roles.{$this->name}";
        $translatedName = __($translationKey);

        if ($translatedName === $translationKey) {
            $translatedName = $this->name;
        }

        return [
            'id' => $this->resource->id,
            'name' => $translatedName,
            'permissions' => $this->getPermissionGrouped(),
        ];
    }

    private function getPermissionGrouped(): array
    {
        $permissionGrouped = [];
        foreach (Permission::grouped() as $group => $permissions) {
            $group = __('permissions.group.' . $group);

            $permissionGrouped[$group] = collect($permissions)->map(function ($permission) {
                return [
                    'id' => $permission,
                    'name' => __('permissions.' . $permission->value),
                    'checked' => $this->resource->permissions->contains('name', $permission->value),
                ];
            });
        }
        return $permissionGrouped;
    }
}

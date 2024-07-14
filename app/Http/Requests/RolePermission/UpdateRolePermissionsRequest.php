<?php

namespace App\Http\Requests\RolePermission;

use App\Constants\Permission;
use Illuminate\Foundation\Http\FormRequest;

class UpdateRolePermissionsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $permissionValues = array_map(fn ($permission) => $permission->value, Permission::cases());

        return [
            'permissions' => ['required', 'array'],
            'permissions.*' => [
                'required',
                'string',
                'in:' . implode(',', $permissionValues)
            ]
        ];
    }

}

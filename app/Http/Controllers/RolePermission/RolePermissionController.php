<?php

namespace App\Http\Controllers\RolePermission;

use App\Http\Controllers\Controller;
use App\Http\Requests\RolePermission\CreateRoleRequest;
use App\Http\Requests\RolePermission\UpdateRolePermissionsRequest;
use App\Http\Resources\RolePermission\RolePermissionDetailResource;
use App\Http\Resources\RolePermission\RoleResource;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;
use Spatie\Permission\Models\Role;

class RolePermissionController extends Controller
{
    public function index(): Response
    {
        $roles = Role::select([
            'id',
            'name',
        ])->get();

        return Inertia::render('RolePermissions/Index', [
            'roles' => fn () => RoleResource::collection($roles),
        ]);
    }

    public function store(CreateRoleRequest $request): RedirectResponse
    {
        Role::create($request->validated());

        return back();
    }

    public function edit(Role $role): Response
    {
        return Inertia::render('RolePermissions/Edit', [
            'role' => new RolePermissionDetailResource($role),
        ]);
    }

    public function update(UpdateRolePermissionsRequest $request, Role $role): RedirectResponse
    {
        $validatedPermissions = $request->validated()['permissions'];
        $role->syncPermissions($validatedPermissions);

        return back();
    }
}

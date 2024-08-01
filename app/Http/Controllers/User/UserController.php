<?php

namespace App\Http\Controllers\User;

use App\Constants\PolicyName;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\UpdateUserRolesRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Inertia\Response;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function index(): Response
    {
        $this->authorize(PolicyName::VIEW_ANY->value, User::class);

        $users = User::select('id', 'name', 'email', 'created_at')
            ->with('roles:name')
            ->paginate(10)
            ->onEachSide(1)
            ->withQueryString();

        $roles = Role::select('id', 'name')->get();

        return inertia('Users/Index', [
            'users' => fn () => $users,
            'roles' => fn () => $roles,
        ]);
    }

    public function updateRoles(UpdateUserRolesRequest $request, User $user): RedirectResponse
    {
        $this->authorize(PolicyName::UPDATE_ROLE->value, $user);

        $user->syncRoles($request->input('roles'));

        return back();
    }

}

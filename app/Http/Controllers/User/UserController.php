<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use Inertia\Response;

class UserController extends Controller
{
    public function index(): Response
    {
        $users = User::select('id', 'name', 'email', 'created_at')
            ->with('roles:name')
            ->paginate(10)
            ->onEachSide(1)
            ->withQueryString();

        return inertia('Users/Index', [
            'users' => fn () => $users,
        ]);
    }

}

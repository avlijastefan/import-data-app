<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use App\Http\Requests\UserStoreRequest;
use App\Http\Requests\UserUpdateRequest;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with('permissions')->paginate(10);
        return view('users.index', compact('users'));
    }

    public function create()
    {
        $permissions = Permission::all();
        return view('users.create', compact('permissions'));
    }

    public function store(UserStoreRequest $request)
    {
       $user = User::create($request->validated());
       if ($request->filled('permissions')) {
            $user->syncPermissions($request->permissions);
        }

        return redirect()->route('users.index')->with('success', 'User created successfully.');
    }

    public function edit(User $user)
    {
        $permissions = Permission::all();
        $userPermissions = $user->permissions->pluck('name')->toArray();
        return view('users.edit', compact('user', 'permissions', 'userPermissions'));
    }

    public function update(UserUpdateRequest $request, User $user)
    {
        $user->update($request->validated());
        $user->syncPermissions($request->permissions ?? []);

        return redirect()->route('users.index')->with('success', 'User updated successfully.');
    }

    public function destroy(User $user)
    {
        $user->delete();
        return back()->with('success', 'User deleted successfully.');
    }
}

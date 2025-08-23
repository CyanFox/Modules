<?php

namespace Modules\Admin\Http\Controllers;

use Dedoc\Scramble\Attributes\Group;
use Dedoc\Scramble\Attributes\PathParameter;
use Dedoc\Scramble\Attributes\QueryParameter;
use Illuminate\Http\Request;
use Modules\Auth\Actions\Users\CreateUserAction;
use Modules\Auth\Actions\Users\DeleteUserAction;
use Modules\Auth\Actions\Users\UpdateUserAction;
use Modules\Auth\Models\Permission;
use Modules\Auth\Models\Role;
use Modules\Auth\Models\User;
use Modules\Auth\Rules\Password;

#[Group('Admin Users')]
class AdminUsersController
{
    #[QueryParameter('per_page', description: 'Number of users per page', type: 'integer', default: 20, example: 10)]
    public function getUsers(Request $request)
    {
        $user = $request->attributes->get('api_key')->user;

        if (! $user->can('admin.users') || ! $request->attributes->get('api_key')->can('admin.users')) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        return response()->json([
            'message' => 'Users retrieved successfully',
            'users' => User::orderBy('created_at')->paginate($request->query('per_page', 20)),
        ]);
    }

    #[PathParameter('userId', description: 'ID of the user to retrieve', type: 'integer', example: 1)]
    public function getUser(Request $request, $userId)
    {
        $user = $request->attributes->get('api_key')->user;

        if (! $user->can('admin.users') || ! $request->attributes->get('api_key')->can('admin.users')) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $user = User::find($userId);

        if (! $user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        return response()->json([
            'message' => 'User retrieved successfully',
            'user' => $user->load(['roles', 'permissions']),
        ]);
    }

    public function createUser(Request $request)
    {
        $user = $request->attributes->get('api_key')->user;

        if (! $user->can('admin.users.create') || ! $request->attributes->get('api_key')->can('admin.users.create')) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $data = $request->validate([
            'first_name' => 'nullable|string',
            'last_name' => 'nullable|string',
            'email' => 'required|email|unique:users,email',
            'username' => 'required|string|unique:users,username',
            'password' => ['required', 'string', new Password],
            'theme' => 'nullable|string',
            'language' => 'nullable|string|max:255',
            'custom_avatar_url' => 'nullable|url|max:255',
            'oauth_id' => 'nullable|string|max:255',
            'force_change_password' => 'nullable|boolean',
            'force_activate_two_factor' => 'nullable|boolean',
            'disabled' => 'nullable|boolean',
        ]);

        $user = CreateUserAction::run($data);

        $roleIds = Role::whereIn('name', $request->input('groups', []))->pluck('id')->toArray();
        $user->roles()->sync($roleIds);

        $permissionIds = Permission::whereIn('name', $request->input('permissions', []))->pluck('id')->toArray();
        $user->permissions()->sync($permissionIds);

        return response()->json([
            'message' => 'User created successfully',
            'user' => $user->load(['roles', 'permissions']),
        ]);
    }

    #[PathParameter('userId', description: 'ID of the user to update', type: 'integer', example: 1)]
    public function updateUser(Request $request, $userId)
    {
        $user = $request->attributes->get('api_key')->user;

        if (! $user->can('admin.users.update') || ! $request->attributes->get('api_key')->can('admin.users.update')) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $data = $request->validate([
            'first_name' => 'nullable|string',
            'last_name' => 'nullable|string',
            'email' => 'nullable|email|unique:users,email,'.$request->route('userId'),
            'username' => 'nullable|string|unique:users,username,'.$request->route('userId'),
            'password' => ['nullable', 'string', new Password],
            'theme' => 'nullable|string',
            'language' => 'nullable|string|max:255',
            'custom_avatar_url' => 'nullable|url|max:255',
            'oauth_id' => 'nullable|string|max:255',
            'force_change_password' => 'nullable|boolean',
            'force_activate_two_factor' => 'nullable|boolean',
            'disabled' => 'nullable|boolean',
        ]);

        $user = User::find($userId);

        if (! $user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        UpdateUserAction::run($user, $data);

        $roleIds = Role::whereIn('name', $request->input('groups', []))->pluck('id')->toArray();
        $user->roles()->sync($roleIds);

        $permissionIds = Permission::whereIn('name', $request->input('permissions', []))->pluck('id')->toArray();
        $user->permissions()->sync($permissionIds);

        return response()->json([
            'message' => 'User updated successfully',
            'user' => $user->fresh()->load(['roles', 'permissions']),
        ]);
    }

    #[PathParameter('userId', description: 'ID of the user to delete', type: 'integer', example: 1)]
    public function deleteUser(Request $request, $userId)
    {
        $user = $request->attributes->get('api_key')->user;

        if (! $user->can('admin.users.delete') || ! $request->attributes->get('api_key')->can('admin.users.delete')) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $user = User::find($userId);

        if (! $user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        DeleteUserAction::run($user);

        return response()->json([
            'message' => 'User deleted successfully',
        ]);
    }
}

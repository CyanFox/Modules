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
        $apiKey = $request->attributes->get('api_key');

        if (!$apiKey->hasPermission('admin.users')) {
            return $apiKey->sendNoPermissionResponse();
        }

        return apiResponse('Users retrieved successfully', User::orderBy('created_at')->paginate($request->query('per_page', 20)));
    }

    #[PathParameter('userId', description: 'ID of the user to retrieve', type: 'integer', example: 1)]
    public function getUser(Request $request, $userId)
    {
        $apiKey = $request->attributes->get('api_key');

        if (!$apiKey->hasPermission('admin.users')) {
            return $apiKey->sendNoPermissionResponse();
        }

        $user = User::find($userId);

        if (!$user) {
            return apiResponse('User not found', null, false, 404);
        }

        return apiResponse('User retrieved successfully', $user->load(['roles', 'permissions']));
    }

    public function createUser(Request $request)
    {
        $apiKey = $request->attributes->get('api_key');

        if (!$apiKey->hasPermission('admin.users.create')) {
            return $apiKey->sendNoPermissionResponse();
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

        return apiResponse('User created successfully', $user->load(['roles', 'permissions']));
    }

    #[PathParameter('userId', description: 'ID of the user to update', type: 'integer', example: 1)]
    public function updateUser(Request $request, $userId)
    {
        $apiKey = $request->attributes->get('api_key');

        if (!$apiKey->hasPermission('admin.users.update')) {
            return $apiKey->sendNoPermissionResponse();
        }

        $data = $request->validate([
            'first_name' => 'nullable|string',
            'last_name' => 'nullable|string',
            'email' => 'nullable|email|unique:users,email,' . $request->route('userId'),
            'username' => 'nullable|string|unique:users,username,' . $request->route('userId'),
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

        if (!$user) {
            return apiResponse('User not found', null, false, 404);
        }

        UpdateUserAction::run($user, $data);

        $roleIds = Role::whereIn('name', $request->input('groups', []))->pluck('id')->toArray();
        $user->roles()->sync($roleIds);

        $permissionIds = Permission::whereIn('name', $request->input('permissions', []))->pluck('id')->toArray();
        $user->permissions()->sync($permissionIds);

        return apiResponse('User updated successfully', $user->fresh()->load(['roles', 'permissions']));
    }

    #[PathParameter('userId', description: 'ID of the user to delete', type: 'integer', example: 1)]
    public function deleteUser(Request $request, $userId)
    {
        $apiKey = $request->attributes->get('api_key');

        if (!$apiKey->hasPermission('admin.users.delete')) {
            return $apiKey->sendNoPermissionResponse();
        }

        $user = User::find($userId);

        if (!$user) {
            return apiResponse('User not found', null, false, 404);
        }

        DeleteUserAction::run($user);

        return apiResponse('User deleted successfully');
    }
}

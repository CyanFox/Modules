<?php

namespace Modules\Admin\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use Dedoc\Scramble\Attributes\Endpoint;
use Dedoc\Scramble\Attributes\Group;
use Dedoc\Scramble\Attributes\Response;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Password;
use Spatie\QueryBuilder\QueryBuilder;

#[Group('Admin Users')]
class UsersController extends Controller
{
    #[Endpoint(title: 'Get Users', description: 'Supports pagination, filtering and sorting via laravel-query-builder')]
    #[Response(200, 'Users retrieved.', examples: ['application/json' => ['success' => true, 'message' => 'Users retrieved.', 'data' => [
        [
            'id' => 1,
            'first_name' => 'first name',
            'last_name' => 'last name',
            'username' => 'username',
            'email' => 'email',
            'theme' => 'dark',
            'language' => 'en',
            'disabled' => false,
            'created_at' => '2000-01-01T00:00:00.000000Z',
            'updated_at' => '2000-01-01T00:00:00.000000Z',
        ]
    ]]])]
    #[Response(401, 'Unauthenticated.', examples: ['application/json' => ['success' => false, 'message' => 'Unauthenticated.', 'data' => []]])]
    public function getUsers(Request $request)
    {
        if ($request->user()->cannot('admin.users')) {
            return apiResponse('Unauthenticated.', [], false, 401);
        }

        return apiResponse('Users retrieved.', QueryBuilder::for(userModel()->query())
            ->allowedSorts('first_name', 'last_name', 'username', 'email', 'theme', 'language', 'disabled')
            ->allowedFilters('first_name', 'last_name', 'username', 'email', 'theme', 'language', 'disabled')
            ->paginate($request->query('perPage') ?? null));
    }

    #[Endpoint(title: 'Get User')]
    #[Response(200, 'User retrieved.', examples: ['application/json' => ['success' => true, 'message' => 'User retrieved.', 'data' => [
        'id' => 1,
        'first_name' => 'first name',
        'last_name' => 'last name',
        'username' => 'username',
        'email' => 'email',
        'theme' => 'dark',
        'language' => 'en',
        'disabled' => false,
        'avatar_url' => 'avatar url',
        'display_name' => 'display name',
        'permissions' => [],
        'roles' => [],
        'settings' => [],
        'created_at' => '2000-01-01T00:00:00.000000Z',
        'updated_at' => '2000-01-01T00:00:00.000000Z',
    ]]])]
    #[Response(404, 'User not found.', examples: ['application/json' => ['success' => false, 'message' => 'User not found.', 'data' => []]])]
    #[Response(401, 'Unauthenticated.', examples: ['application/json' => ['success' => false, 'message' => 'Unauthenticated.', 'data' => []]])]
    public function getUser(Request $request, int $userId)
    {
        if ($request->user()->cannot('admin.users')) {
            return apiResponse('Unauthenticated.', [], false, 401);
        }

        $user = userModel()->find($userId);
        if (!$user) {
            return apiResponse('User not found.', [], false, 404);
        }

        return apiResponse('User retrieved.', array_merge($user->withoutRelations()->toArray(), [
            'avatar_url' => $user->getAvatar(),
            'display_name' => $user->getDisplayName(),
            'roles' => $user->roles->map(fn($role) => [
                'id' => $role->id,
                'name' => $role->name,
            ]),
            'permissions' => $user->permissions->map(fn($permission) => [
                'id' => $permission->id,
                'name' => $permission->name,
            ]),
            'settings' => $user->userSettings
                ->each(function ($setting) {
                    if (is_string($setting->properties)) {
                        $setting->setAttribute('properties', json_decode($setting->properties, true));
                    }
                })
                ->values(),
        ]));
    }

    #[Endpoint(title: 'Create User')]
    #[Response(200, 'User created.', examples: ['application/json' => ['success' => true, 'message' => 'User created.', 'data' => [
        'id' => 1,
        'first_name' => 'first name',
        'last_name' => 'last name',
        'username' => 'username',
        'email' => 'email',
        'theme' => 'dark',
        'language' => 'en',
        'disabled' => false,
        'created_at' => '2000-01-01T00:00:00.000000Z',
        'updated_at' => '2000-01-01T00:00:00.000000Z',
    ]]])]
    #[Response(401, 'Unauthenticated.', examples: ['application/json' => ['success' => false, 'message' => 'Unauthenticated.', 'data' => []]])]
    public function createUser(Request $request)
    {
        if ($request->user()->cannot('admin.users.create')) {
            return apiResponse('Unauthenticated.', [], false, 401);
        }

        $request->validate([
            'first_name' => 'nullable|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'username' => 'required|string|max:255|unique:users,username',
            'password' => ['required', 'string', Password::defaults()],
            'email' => 'required|email',
            'roles' => 'nullable|array',
            'permissions' => 'nullable|array',
        ]);

        $user = userModel()->create($request->all());

        $user->syncRoles($request->roles);
        $user->syncPermissions($request->permissions);

        return apiResponse('User created.', $user);
    }

    #[Endpoint(title: 'Update User')]
    #[Response(200, 'User updated.', examples: ['application/json' => ['success' => true, 'message' => 'User updated.', 'data' => [
        'id' => 1,
        'first_name' => 'first name',
        'last_name' => 'last name',
        'username' => 'username',
        'email' => 'email',
        'theme' => 'dark',
        'language' => 'en',
        'disabled' => false,
        'created_at' => '2000-01-01T00:00:00.000000Z',
        'updated_at' => '2000-01-01T00:00:00.000000Z',
    ]]])]
    #[Response(404, 'User not found.', examples: ['application/json' => ['success' => false, 'message' => 'User not found.', 'data' => []]])]
    #[Response(401, 'Unauthenticated.', examples: ['application/json' => ['success' => false, 'message' => 'Unauthenticated.', 'data' => []]])]
    public function updateUser(Request $request, int $userId)
    {
        if ($request->user()->cannot('admin.users.update')) {
            return apiResponse('Unauthenticated.', [], false, 401);
        }

        $user = userModel()->find($userId);
        if (!$user) {
            return apiResponse('User not found.', [], false, 404);
        }

        $request->validate([
            'first_name' => 'nullable|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'username' => 'nullable|string|max:255|unique:users,username,' . $userId,
            'password' => ['nullable', 'string', Password::defaults()],
            'email' => 'nullable|email',
            'roles' => 'nullable|array',
            'permissions' => 'nullable|array',
        ]);

        $user->update($request->all());
        $user->syncRoles($request->roles);
        $user->syncPermissions($request->permissions);

        return apiResponse('User updated.', $user);
    }

    #[Endpoint(title: 'Delete User')]
    #[Response(200, 'User deleted.', examples: ['application/json' => ['success' => true, 'message' => 'User deleted.', 'data' => []]])]
    #[Response(404, 'User not found.', examples: ['application/json' => ['success' => false, 'message' => 'User not found.', 'data' => []]])]
    #[Response(401, 'Unauthenticated.', examples: ['application/json' => ['success' => false, 'message' => 'Unauthenticated.', 'data' => []]])]
    public function deleteUser(Request $request, int $userId)
    {
        if ($request->user()->cannot('admin.users.delete')) {
            return apiResponse('Unauthenticated.', [], false, 401);
        }

        $user = userModel()->find($userId);
        if (!$user) {
            return apiResponse('User not found.', [], false, 404);
        }

        $user->delete();

        return apiResponse('User deleted.');
    }
}

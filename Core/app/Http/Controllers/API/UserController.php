<?php

namespace Modules\Core\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Dedoc\Scramble\Attributes\Endpoint;
use Dedoc\Scramble\Attributes\Group;
use Dedoc\Scramble\Attributes\Response;
use Illuminate\Http\Request;
use Modules\Core\Enums\SettingsProperty;

#[Group('User')]
class UserController extends Controller
{
    #[Endpoint(title: 'Get current user')]
    #[Response(200, 'User retrieved.', examples: ['application/json' => ['success' => true, 'message' => 'Setting retrieved.', 'data' => [
        'id' => 1,
        'first_name' => 'first name',
        'last_name' => 'last name',
        'username' => 'username',
        'email' => 'email',
        'theme' => 'theme',
        'language' => 'language',
        'disabled' => false,
        'created_at' => '2000-01-01T00:00:00.000000Z',
        'updated_at' => '2000-01-01T00:00:00.000000Z',
        'avatar_url' => 'avatar url',
        'display_name' => 'display name',
        'roles' => [],
        'permissions' => [],
        'settings' => []
    ]]])]
    #[Response(401, 'Unauthenticated.', examples: ['application/json' => ['success' => false, 'message' => 'Unauthenticated.', 'data' => []]])]
    public function me(Request $request)
    {
        $user = $request->user();

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
                ->reject(fn($setting) => $setting->getProperty(SettingsProperty::INTERNAL) === true)
                ->each(function ($setting) {
                    if (is_string($setting->properties)) {
                        $setting->setAttribute('properties', json_decode($setting->properties, true));
                    }
                })
                ->values(),
        ]));
    }

    #[Endpoint(title: 'Get current user permissions')]
    #[Response(200, 'Permissions retrieved.', examples: ['application/json' => ['success' => true, 'message' => 'Permissions retrieved.', 'data' => [
        [
            'id' => 1,
            'name' => 'test',
        ]
    ]]])]
    #[Response(401, 'Unauthenticated.', examples: ['application/json' => ['success' => false, 'message' => 'Unauthenticated.', 'data' => []]])]
    public function permissions(Request $request)
    {
        $user = $request->user();

        return apiResponse('Permissions retrieved.', $user->permissions->map(fn($permission) => [
            'id' => $permission->id,
            'name' => $permission->name,
        ]));
    }

    #[Endpoint(title: 'Get current user roles')]
    #[Response(200, 'Roles retrieved.', examples: ['application/json' => ['success' => true, 'message' => 'Roles retrieved.', 'data' => [
        [
            'id' => 1,
            'name' => 'test',
        ]
    ]]])]
    #[Response(401, 'Unauthenticated.', examples: ['application/json' => ['success' => false, 'message' => 'Unauthenticated.', 'data' => []]])]
    public function roles(Request $request)
    {
        $user = $request->user();

        return apiResponse('Roles retrieved.', $user->roles->map(fn($role) => [
            'id' => $role->id,
            'name' => $role->name,
        ]));
    }

    #[Endpoint(title: 'Get current user settings')]
    #[Response(200, 'Settings retrieved.', examples: ['application/json' => ['success' => true, 'message' => 'Settings retrieved.', 'data' => [
        [
            'id' => 1,
            'key' => 'key',
            'value' => 'value',
            'properties' => [],
            'created_at' => '2000-01-01T00:00:00.000000Z',
            'updated_at' => '2000-01-01T00:00:00.000000Z'
        ]
    ]]])]
    #[Response(401, 'Unauthenticated.', examples: ['application/json' => ['success' => false, 'message' => 'Unauthenticated.', 'data' => []]])]
    public function settings(Request $request)
    {
        $user = $request->user();

        return apiResponse('Settings retrieved.', $user->userSettings
            ->reject(fn($setting) => $setting->getProperty(SettingsProperty::INTERNAL) === true)
            ->each(function ($setting) {
                if (is_string($setting->properties)) {
                    $setting->setAttribute('properties', json_decode($setting->properties, true));
                }
            })
            ->values()
        );
    }
}

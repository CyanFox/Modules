<?php

namespace Modules\Core\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use Dedoc\Scramble\Attributes\Endpoint;
use Dedoc\Scramble\Attributes\Group;
use Dedoc\Scramble\Attributes\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Modules\Core\Enums\SettingsProperty;

#[Group('User')]
class UserController extends Controller
{
    #[Endpoint(title: 'Get Current user')]
    #[Response(200, 'User retrieved.', examples: ['application/json' => ['success' => true, 'message' => 'Setting retrieved.', 'data' => [
        'id' => 1,
        'first_name' => '',
        'last_name' => '',
        'username' => '',
        'email' => '',
        'theme' => '',
        'language' => '',
        'disabled' => false,
        'created_at' => '2000-01-01T00:00:00.000000Z',
        'updated_at' => '2000-01-01T00:00:00.000000Z',
        'avatar_url' => '',
        'display_name' => '',
        'roles' => [],
        'permissions' => [],
        'settings' => [],
    ]]])]
    #[Response(401, 'Unauthenticated.', examples: ['application/json' => ['success' => false, 'message' => 'Unauthenticated.', 'data' => []]])]
    public function me(Request $request)
    {
        $user = $request->user();

        return apiResponse('User retrieved.', array_merge($user->withoutRelations()->toArray(), [
            'avatar_url' => $user->getAvatar(),
            'display_name' => $user->getDisplayName(),
            'notifications' => $user->unreadNotifications->map(fn($notification) => [
                'id' => $notification->id,
                'type' => $notification->type,
                'data' => $notification->data,
                'read_at' => $notification->read_at,
                'created_at' => $notification->created_at,
            ])->values()->all(),
            'roles' => $user->roles->map(fn ($role) => [
                'id' => $role->id,
                'name' => $role->name,
            ]),
            'permissions' => $user->getAllPermissions()->map(fn ($permission) => [
                'id' => $permission->id,
                'name' => $permission->name,
            ]),
            'settings' => $user->userSettings
                ->reject(fn ($setting) => $setting->getProperty(SettingsProperty::INTERNAL) === true)
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
            'name' => '',
        ],
    ]]])]
    #[Response(401, 'Unauthenticated.', examples: ['application/json' => ['success' => false, 'message' => 'Unauthenticated.', 'data' => []]])]
    public function permissions(Request $request)
    {
        $user = $request->user();

        return apiResponse('Permissions retrieved.', $user->getAllPermissions()->map(fn ($permission) => [
            'id' => $permission->id,
            'name' => $permission->name,
        ]));
    }

    #[Endpoint(title: 'Get current user roles')]
    #[Response(200, 'Roles retrieved.', examples: ['application/json' => ['success' => true, 'message' => 'Roles retrieved.', 'data' => [
        [
            'id' => 1,
            'name' => '',
        ],
    ]]])]
    #[Response(401, 'Unauthenticated.', examples: ['application/json' => ['success' => false, 'message' => 'Unauthenticated.', 'data' => []]])]
    public function roles(Request $request)
    {
        $user = $request->user();

        return apiResponse('Roles retrieved.', $user->roles->map(fn ($role) => [
            'id' => $role->id,
            'name' => $role->name,
        ]));
    }

    #[Endpoint(title: 'Get current user settings')]
    #[Response(200, 'Settings retrieved.', examples: ['application/json' => ['success' => true, 'message' => 'Settings retrieved.', 'data' => [
        [
            'id' => 1,
            'key' => '',
            'value' => '',
            'properties' => [],
            'created_at' => '2000-01-01T00:00:00.000000Z',
            'updated_at' => '2000-01-01T00:00:00.000000Z',
        ],
    ]]])]
    #[Response(401, 'Unauthenticated.', examples: ['application/json' => ['success' => false, 'message' => 'Unauthenticated.', 'data' => []]])]
    public function settings(Request $request)
    {
        $user = $request->user();

        return apiResponse('Settings retrieved.', $user->userSettings
            ->reject(fn ($setting) => $setting->getProperty(SettingsProperty::INTERNAL) === true)
            ->each(function ($setting) {
                if (is_string($setting->properties)) {
                    $setting->setAttribute('properties', json_decode($setting->properties, true));
                }
            })
            ->values()
        );
    }

    #[Endpoint(title: 'Get current user unread notifications')]
    #[Response(200, 'Notifications retrieved.', examples: ['application/json' => ['success' => true, 'message' => 'Notifications retrieved.', 'data' => [
        [
            'id' => 'uuid',
            'type' => '',
            'date' => [],
            'read_at' => null,
            'created_at' => '2000-01-01T00:00:00.000000Z',
            'updated_at' => '2000-01-01T00:00:00.000000Z',
        ],
    ]]])]
    #[Response(401, 'Unauthenticated.', examples: ['application/json' => ['success' => false, 'message' => 'Unauthenticated.', 'data' => []]])]
    public function notifications(Request $request)
    {
        $user = $request->user();

        return apiResponse('Notifications retrieved.', $user->unreadNotifications->map(fn($notification) => [
            'id' => $notification->id,
            'type' => $notification->type,
            'data' => $notification->data,
            'read_at' => $notification->read_at,
            'created_at' => $notification->created_at,
        ])->values()->all());
    }

    #[Endpoint(title: 'Mark notifications as read')]
    #[Response(200, 'Notifications marked as read.', examples: ['application/json' => ['success' => true, 'message' => 'Notifications marked as read.', 'data' => []]])]
    public function markAsRead(Request $request)
    {
        $user = $request->user();
        if ($request->query('notificationId')) {
            $user->unreadNotifications->where('id', $request->query('notificationId'))->markAsRead();
        } else {
            $user->unreadNotifications->markAsRead();
        }

        return apiResponse('Notifications marked as read.');
    }

    #[Endpoint(title: 'Check password')]
    #[Response(200, 'Password checked.', examples: ['application/json' => ['success' => true, 'message' => 'Password checked.', 'data' => [
        'valid' => true,
    ]]])]
    #[Response(401, 'Unauthenticated.', examples: ['application/json' => ['success' => false, 'message' => 'Unauthenticated.', 'data' => []]])]
    public function checkPassword(Request $request)
    {
        $user = $request->user();
        $password = $request->input('password');

        return apiResponse('Password checked.', [
            'valid' => Hash::check($password, $user->password),
        ]);
    }
}

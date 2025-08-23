<?php

namespace Modules\Auth\Http\Controllers;

use Dedoc\Scramble\Attributes\Group;
use Dedoc\Scramble\Attributes\QueryParameter;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Modules\Auth\Actions\Users\UpdateUserAction;
use Spatie\Activitylog\Models\Activity;

#[Group('Account')]
class AccountController
{
    public function getUser(Request $request)
    {
        $user = $request->attributes->get('api_key')->user;

        $user->load('roles:id,name', 'permissions:id,name');

        return response()->json([
            'message' => 'Account retrieved successfully',
            'user' => array_merge(
                $user->toArray(),
                [
                    'roles' => $user->roles->map(fn ($role) => [
                        'id' => $role->id,
                        'name' => $role->name,
                    ]),
                    'permissions' => $user->permissions->map(fn ($perm) => [
                        'id' => $perm->id,
                        'name' => $perm->name,
                    ]),
                ]
            ),
        ]);
    }

    public function updateAccount(Request $request)
    {
        $user = $request->attributes->get('api_key')->user;
        $request->validate([
            'first_name' => 'nullable|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'username' => 'nullable|string|max:255|unique:users,username,'.$user->id,
            'theme' => 'nullable|string|in:light,dark',
            'language' => 'nullable|string|in:en,de',
            'custom_avatar_url' => 'nullable|url|max:2048',
        ]);

        UpdateUserAction::run($user, $request->only([
            'first_name',
            'last_name',
            'username',
            'theme',
            'language',
            'custom_avatar_url',
        ]));

        return response()->json([
            'message' => 'Account updated successfully',
            'user' => $user,
        ]);
    }

    #[QueryParameter('per_page', description: 'Number of activity entries per page', type: 'integer', default: 20, example: 10)]
    public function getActivity(Request $request)
    {
        $user = $request->attributes->get('api_key')->user;

        $activityLog = Activity::where('causer_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->paginate($request->query('per_page', 20));

        return response()->json([
            'message' => 'Activity retrieved successfully',
            'activity_log' => $activityLog->map(function (Activity $activity) {
                $properties = json_decode($activity->properties, true) ?? [];

                return [
                    'id' => $activity->id,
                    'description' => $activity->description,
                    'event' => $activity->event,
                    'created_at' => $activity->created_at->toIso8601String(),
                    'properties' => $properties,
                    'old_values' => $properties['old'] ?? [],
                    'new_values' => $properties['attributes'] ?? [],
                ];
            }),
        ]);
    }

    #[QueryParameter('per_page', description: 'Number of session entries per page', type: 'integer', default: 20, example: 10)]
    public function getSessions(Request $request)
    {
        $user = $request->attributes->get('api_key')->user;

        $sessions = $user->sessions()
            ->orderBy('last_activity', 'desc')
            ->paginate($request->query('per_page', 20));

        return response()->json([
            'message' => 'Sessions retrieved successfully',
            'sessions' => $sessions->map(function ($session) {
                return [
                    'id' => $session->id,
                    'ip_address' => $session->ip_address,
                    'user_agent' => $session->user_agent,
                    'last_activity' => Carbon::createFromTimestamp($session->last_activity)->toIso8601String(),
                ];
            }),
        ]);
    }
}

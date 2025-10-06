<?php

namespace Modules\Auth\Http\Controllers;

use Dedoc\Scramble\Attributes\Group;
use Dedoc\Scramble\Attributes\PathParameter;
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

        return apiResponse('Account retrieved successfully',
            array_merge(
                $user->toArray(),
                [
                    'roles' => $user->roles->map(fn($role) => [
                        'id' => $role->id,
                        'name' => $role->name,
                    ]),
                    'permissions' => $user->permissions->map(fn($perm) => [
                        'id' => $perm->id,
                        'name' => $perm->name,
                    ]),
                ]
            ));
    }

    public function updateAccount(Request $request)
    {
        $user = $request->attributes->get('api_key')->user;
        $request->validate([
            'first_name' => 'nullable|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'username' => 'nullable|string|max:255|unique:users,username,' . $user->id,
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

        return apiResponse('Account updated successfully', $user);
    }

    public function hasPermission(Request $request)
    {
        $user = $request->attributes->get('api_key')->user;

        $request->validate([
            'permission' => 'required|string|exists:permissions,name',
        ]);

        return apiResponse('Permission check completed successfully', [
            'has_permission' => $user->can($request->input('permission')),
        ]);
    }

    public function uploadAvatar(Request $request)
    {
        $user = $request->attributes->get('api_key')->user;

        $request->validate([
            'avatar' => 'required|image:allow_svg|max:10000',
        ]);

        $request->file('avatar')->storeAs('avatars', $user->id.'.png', 'public');

        return apiResponse('Avatar uploaded successfully', $user);
    }

    #[QueryParameter('per_page', description: 'Number of activity entries per page', type: 'integer', default: 20, example: 10)]
    public function getActivity(Request $request)
    {
        $user = $request->attributes->get('api_key')->user;

        $activityLog = Activity::where('causer_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->paginate($request->query('per_page', 20));

        return apiResponse('Activity retrieved successfully',
            $activityLog->map(function (Activity $activity) {
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
            }));
    }

    #[QueryParameter('per_page', description: 'Number of session entries per page', type: 'integer', default: 20, example: 10)]
    public function getSessions(Request $request)
    {
        $user = $request->attributes->get('api_key')->user;

        $sessions = $user->sessions()
            ->orderBy('last_activity', 'desc')
            ->paginate($request->query('per_page', 20));

        return apiResponse('Sessions retrieved successfully',
            $sessions->map(function ($session) {
                return [
                    'id' => $session->id,
                    'ip_address' => $session->ip_address,
                    'user_agent' => $session->user_agent,
                    'last_activity' => Carbon::createFromTimestamp($session->last_activity)->toIso8601String(),
                ];
            }));
    }

    public function revokeSessions(Request $request)
    {
        $user = $request->attributes->get('api_key')->user;

        $user->revokeOtherSessions();

        return apiResponse('All sessions revoked successfully');
    }

    #[PathParameter('sessionId', description: 'ID of the session to revoke', type: 'string', example: 'PHvAlcFhPKbwTAUPNbFsIlN4cnsVg9N6Dp1NbpEM')]
    public function revokeSession(Request $request, $sessionId)
    {
        $user = $request->attributes->get('api_key')->user;

        $session = $user->sessions()->where('id', $sessionId)->first();

        if (!$session) {
            return apiResponse('Session not found', null, false, 404);
        }

        $session->delete();

        return apiResponse('Session revoked successfully');
    }

    public function activateTwoFactor(Request $request)
    {
        $user = $request->attributes->get('api_key')->user;

        if ($user->two_factor_enabled) {
            return apiResponse('Two-factor authentication is already enabled', null, false, 400);
        }

        $recoveryCodes = $user->generateRecoveryCodes();

        UpdateUserAction::run($user, [
            'two_factor_enabled' => true,
        ]);

        $user->revokeOtherSessions();

        return apiResponse('Two-factor authentication enabled successfully', [
            'two_factor_secret' => decrypt($user->two_factor_secret),
            'two_factor_qr_code_url' => 'data:image/svg+xml;base64,' . $user->getTwoFactorImage(),
            'recovery_codes' => $recoveryCodes,
        ]);
    }

    public function disableTwoFactor(Request $request)
    {
        $user = $request->attributes->get('api_key')->user;

        if (!$user->two_factor_enabled) {
            return apiResponse('Two-factor authentication is not enabled', null, false, 400);
        }

        UpdateUserAction::run($user, [
            'two_factor_secret' => null,
            'two_factor_enabled' => false,
        ]);

        $user->generateTwoFASecret();
        $user->recoveryCodes()->delete();

        return apiResponse('Two-factor authentication disabled successfully');
    }

    #[QueryParameter('use_recovery_code', description: 'Set to true if you are using a recovery code instead of a 2FA code', type: 'boolean', default: false, example: false)]
    public function verifyTwoFactorCode(Request $request)
    {
        $user = $request->attributes->get('api_key')->user;
        $useRecoveryCode = $request->query('use_recovery_code', false);

        if (!$user->two_factor_enabled) {
            return apiResponse('Two-factor authentication is not enabled', null, false, 400);
        }

        $request->validate([
            'code' => 'required|string',
        ]);

        $code = $request->input('code');

        if ($user->checkTwoFACode($code, $useRecoveryCode)) {
            return apiResponse('Two-factor or recovery code is valid');
        }

        return apiResponse('Invalid two-factor or recovery code', null, false, 400);
    }

    public function regenerateRecoveryCodes(Request $request)
    {
        $user = $request->attributes->get('api_key')->user;

        if (!$user->two_factor_enabled) {
            return apiResponse('Two-factor authentication is not enabled', null, false, 400);
        }

        $recoveryCodes = $user->generateRecoveryCodes();

        return apiResponse('Recovery codes regenerated successfully', $recoveryCodes);
    }

    public function deleteAccount(Request $request)
    {
        $user = $request->attributes->get('api_key')->user;

        if (!settings('auth.profile.enable.delete_account')) {
            return apiResponse('Account deletion is disabled', null, false, 403);
        }

        $user->delete();

        return apiResponse('Account deleted successfully');
    }

}

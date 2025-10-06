<?php

namespace Modules\Auth\Http\Controllers;

use Dedoc\Scramble\Attributes\Group;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Modules\Auth\Actions\Users\CreateUserAction;
use Modules\Auth\Emails\NewSessionMail;
use Modules\Auth\Models\Session;
use Modules\Auth\Models\User;
use Modules\Auth\Rules\Password;

#[Group('Authentication')]
class AuthController
{
    public function lookupUser(Request $request)
    {
        $user = User::where('username', $request->route('username'))->first();

        if (!$user) {
            return apiResponse('User not found', null, false, 404);
        }

        return apiResponse('User found', [
            'username' => $user->username,
            'avatar' => $user->avatar(),
        ]);
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
            'api_key_name' => 'nullable|string',
            'two_factor_code' => 'nullable|string',
            'is_recovery_code' => 'nullable|boolean',
        ]);

        if ($request->filled('two_factor_code') || $request->input('is_recovery_code', false)) {
            return $this->checkTwoFactorCode($request);
        }

        if (!auth()->attempt($request->only('username', 'password'))) {
            activity()
                ->performedOn($this->user)
                ->causedByAnonymous()
                ->log('api.auth.login_failed');
            return apiResponse('Invalid credentials', null, false, 401);
        }

        $user = auth()->user();

        if ($user->disabled) {
            activity()
                ->performedOn($this->user)
                ->causedByAnonymous()
                ->log('api.auth.login_failed');

            auth()->logout();
            return apiResponse('User account is disabled', null, false, 403);
        }

        if ($user->two_factor_enabled) {
            auth()->logout();
            return apiResponse('Two-factor authentication required', ['two_factor' => true], false, 403);
        }

        return $this->generateLoginToken($user, $request->input('api_key_name'));
    }

    public function checkTwoFactorCode(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
            'two_factor_code' => 'required|string',
            'is_recovery_code' => 'nullable|boolean',
            'api_key_name' => 'nullable|string',
        ]);

        if (!auth()->attempt($request->only('username', 'password'))) {
            return apiResponse('Invalid credentials', null, false, 401);
        }

        $user = auth()->user();

        if ($user->disabled) {
            auth()->logout();
            return apiResponse('User account is disabled', null, false, 403);
        }

        if (!$user->two_factor_enabled) {
            return apiResponse('Two-factor authentication not enabled', null, false, 400);
        }

        if ($request->input('is_recovery_code', false)) {
            foreach ($user->recoveryCodes as $recoveryCode) {
                if (Hash::check($request->input('two_factor_code'), $recoveryCode->code)) {
                    $recoveryCode->delete();
                    if (!Session::where('user_id', $user->id)
                        ->where('ip_address', request()->ip())
                        ->exists()) {
                        $mail = new NewSessionMail($user->email, $user->username, $user->first_name, $user->last_name);

                        Mail::send($mail);
                    }

                    activity()
                        ->performedOn($user)
                        ->causedByAnonymous()
                        ->log('api.auth.login.recovery_code');

                    return $this->generateLoginToken($user, $request->input('api_key_name'));
                }
            }

            activity()
                ->performedOn($user)
                ->causedByAnonymous()
                ->log('api.auth.login.recovery_code.failed');

            return apiResponse('Invalid recovery code', null, false, 401);
        }

        if ($user->checkTwoFACode($request->input('two_factor_code'))) {
            if (!Session::where('user_id', $user->id)
                    ->where('ip_address', request()->ip())
                    ->exists() && settings('auth.emails.new_session.enabled', config('auth.emails.new_session.enabled'))) {
                $mail = new NewSessionMail($user->email, $user->username, $user->first_name, $user->last_name);

                Mail::send($mail);
            }

            activity()
                ->performedOn($user)
                ->causedByAnonymous()
                ->log('api.auth.login.two_factor');

            return $this->generateLoginToken($user, $request->input('api_key_name'));
        }

        activity()
            ->performedOn($user)
            ->causedByAnonymous()
            ->log('api.auth.login.two_factor.failed');

        return apiResponse('Invalid two-factor code', null, false, 401);
    }

    public function logout(Request $request)
    {
        $request->attributes->get('api_key')->delete();

        return apiResponse('Logged out');
    }

    private function generateLoginToken($user, $keyName = null, $message = 'Login successful'): JsonResponse
    {
        $key = Str::random(32);

        $apiKey = $user->apiKeys()->create([
            'name' => $keyName ?? 'API Key ' . formatDateTime(now()),
            'key' => Hash::make($key),
            'connected_device' => true,
        ]);

        return apiResponse($message, [
            'user' => $user,
            'api_key_id' => $apiKey->id,
            'api_key' => $apiKey->id . '-' . $key,
        ]);
    }

    public function register(Request $request)
    {
        $request->validate([
            'first_name' => 'nullable',
            'last_name' => 'nullable',
            'email' => 'required|email|unique:users,email',
            'username' => 'required|unique:users,username',
            'password' => ['required', new Password],
        ], [
            'email.unique' => __('auth::register.email_unique'),
            'username.unique' => __('auth::register.username_unique'),
        ]);

        if (!settings('auth.register.enabled')) {
            return apiResponse('Registration is disabled', null, false, 403);
        }

        $user = CreateUserAction::run([
            'first_name' => $request->input('first_name'),
            'last_name' => $request->input('last_name'),
            'email' => $request->input('email'),
            'username' => $request->input('username'),
            'password' => Hash::make($request->input('password')),
        ]);

        activity()
            ->performedOn($user)
            ->causedBy($user)
            ->log('api.auth.register');

        return $this->generateLoginToken($user, $request->input('api_key_name'), 'Registration successful');
    }
}

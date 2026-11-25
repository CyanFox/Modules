<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Laravel\Socialite\Two\InvalidStateException;
use Laravel\Socialite\Two\User;
use Modules\Auth\Models\OAuthProvider;
use Modules\Auth\Socialite\CustomOAuthProvider;
use Modules\Core\Enums\SettingsProperty;
use Modules\Core\Facades\UserSettings;
use Modules\Core\Models\UserSetting;
use Nwidart\Modules\Facades\Module;

Route::group(['prefix' => 'auth', 'as' => 'auth.'], function () {
    Route::group(['middleware' => ['guest', 'throttle:20,1']], function () {
        Route::livewire('login', 'auth::login')->name('login');
        Route::livewire('forgot-password', 'auth::forgot-password')->name('forgot-password');
        Route::livewire('register', 'auth::register')->name('register');
    });

    Route::group(['middleware' => ['auth']], function () {
        Route::livewire('force-password-change', 'auth::force-password-change')->name('force-password-change');
        Route::livewire('force-mfa', 'auth::force-mfa')->name('force-mfa');
        Route::get('logout', function () {
            auth()->logout();

            return redirect()->route('auth.login');
        })->name('logout');
    });
});

Route::group(['prefix' => 'oauth', 'as' => 'oauth.'], function () {
    Route::get('{provider}/redirect', function (string $provider) {
        $oauthProvider = OAuthProvider::findOrFail($provider);

        $socialiteProvider = new CustomOAuthProvider(
            request(),
            $oauthProvider->client_id,
            $oauthProvider->client_secret,
            route('oauth.callback', ['provider' => $oauthProvider->id]),
            oauthProvider: $oauthProvider,
        );

        return $socialiteProvider->redirect();
    })->name('redirect');

    Route::get('{provider}/callback', function (string $provider) {
        $oauthProvider = OAuthProvider::findOrFail($provider);
        $socialiteProvider = new CustomOAuthProvider(
            request(),
            $oauthProvider->client_id,
            $oauthProvider->client_secret,
            route('oauth.callback', ['provider' => $oauthProvider->id]),
            oauthProvider: $oauthProvider,
        );
        try {
            $socialiteUser = $socialiteProvider->user();
        } catch (InvalidStateException) {
            abort(400, 'Invalid OAuth state.');
        }

        if (! $socialiteUser instanceof User) {
            abort(422, 'The OAuth provider did not return a valid user.');
        }

        $settingKey = 'auth.oauth.'.$oauthProvider->id.'.id';
        $linkedSetting = UserSetting::where('key', $settingKey)
            ->where('value', $socialiteUser->getId())
            ->first();

        if ($linkedSetting) {
            $linkedUser = userModel()->find($linkedSetting->user_id);
            if (! $linkedUser) {
                abort(422, 'The linked user could not be found.');
            }
            if ($linkedUser->disabled) {
                abort(403, 'The linked user is disabled.');
            }

            if (Auth::check() && Auth::id() !== $linkedUser->id) {
                abort(409, 'This OAuth account is already linked to another user.');
            }

            Auth::login($linkedUser);

            return redirect()->intended();
        }

        if (Auth::check()) {
            if (! $oauthProvider->account_linking) {
                abort(403, 'Account linking is disabled for this OAuth provider.');
            }

            UserSettings::set(Auth::id(), $settingKey, $socialiteUser->getId(), true, [SettingsProperty::INTERNAL => true, SettingsProperty::HIDDEN => true]);

            return redirect()->route('account.profile', ['tab' => 'oauth']);
        }

        $userModel = userModel();
        $userData = array_intersect_key(
            $socialiteUser->attributes,
            array_flip($userModel->getFillable())
        );
        $userData['password'] = null;

        foreach (['username', 'email'] as $requiredField) {
            if (blank($userData[$requiredField] ?? null)) {
                abort(422, 'The OAuth provider did not return a valid '.$requiredField.'.');
            }
        }

        $user = $userModel->create($userData);
        UserSettings::set($user->id, $settingKey, $socialiteUser->getId(), properties: [SettingsProperty::INTERNAL => true, SettingsProperty::HIDDEN => true]);
        Auth::login($user);

        return redirect()->intended();
    })->name('callback');
});

if (Module::isEnabled('Admin')) {
    Route::livewire('admin/settings/auth', 'auth::admin.oauth-providers')->name('admin.settings.auth')->can('admin.settings')->middleware('auth');
}

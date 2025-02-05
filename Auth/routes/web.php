<?php

use Illuminate\Support\Facades\Route;
use Laravel\Socialite\Facades\Socialite;
use Modules\Auth\Livewire\Account\Profile;
use Modules\Auth\Livewire\Auth\ForgotPassword;
use Modules\Auth\Livewire\Auth\Login;
use Modules\Auth\Livewire\Auth\Register;
use Modules\Auth\Models\User;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::group(['prefix' => 'auth', 'as' => 'auth.'], function () {
    Route::group(['middleware' => ['guest', 'throttle:10,1']], function () {
        Route::get('login', Login::class)->name('login');

        Route::get('register', Register::class)->name('register');
        Route::get('forgot-password', ForgotPassword::class)->name('forgot-password');
    });

    Route::group(['middleware' => ['auth']], function () {
        Route::get('logout', function () {
            auth()->logout();

            return redirect()->route('auth.login');
        })->name('logout');
    });
});

Route::group(['prefix' => 'account', 'as' => 'account.', 'middleware' => ['auth']], function () {
    Route::get('profile', Profile::class)->name('profile');
});

Route::group(['prefix' => 'oauth', 'as' => 'oauth.'], function () {
    Route::get('{provider}', function ($provider) {
        if (! settings('auth.oauth.enable')) {
            abort(404);
        }

        return Socialite::driver($provider)->redirect();
    })->name('redirect');

    Route::get('{provider}/callback', function ($provider) {
        if (! settings('auth.oauth.enable')) {
            abort(404);
        }

        $providerUser = Socialite::driver($provider)->user();
        $user = User::where('oauth_id', $providerUser->id)->first();

        if (! $user) {
            try {
                $user = User::create([
                    'username' => $providerUser->getName(),
                    'email' => $providerUser->getEmail(),
                    'oauth_id' => $providerUser->getId(),
                ]);
            } catch (Exception) {
                $user = User::create([
                    'username' => $providerUser->getName().$providerUser->getId(),
                    'email' => $providerUser->getEmail().$providerUser->getId(),
                    'oauth_id' => $providerUser->getId(),
                ]);
            }

            $user->generateTwoFASecret();
        }

        auth()->login($user);
        if (settings('auth.login.redirect')) {
            return redirect(settings('auth.login.redirect'));
        }

        return redirect('/');
    })->name('callback');
});

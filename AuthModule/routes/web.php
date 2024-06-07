<?php

use Illuminate\Support\Facades\Route;
use Modules\AuthModule\Livewire\Account\ForceActivateTwoFactor;
use Modules\AuthModule\Livewire\Account\ForceChangePassword;
use Modules\AuthModule\Livewire\Account\Profile;
use Modules\AuthModule\Livewire\Auth\ForgotPassword;
use Modules\AuthModule\Livewire\Auth\Login;
use Modules\AuthModule\Livewire\Auth\Register;

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
    Route::group(['middleware' => 'guest'], function () {
        Route::get('login', Login::class)->name('login');

        if (setting('authmodule.enable.register')) {
            Route::get('register', Register::class)->name('register');
        }

        if (setting('authmodule.enable.forgot_password')) {
            Route::get('forgot-password', ForgotPassword::class)->name('forgot-password');
            Route::get('forgot-password/{resetToken}', ForgotPassword::class)->name('forgot-password');
        }
    });

    Route::get('logout', function () {
        auth()->logout();

        return redirect()->route('auth.login');
    })->name('logout')->middleware('auth');
});

Route::group(['middleware' => ['auth', 'language', 'disabled']], function () {
    Route::group(['prefix' => 'account', 'as' => 'account.', 'middleware' => 'force_actions'], function () {
        Route::get('profile', Profile::class)->name('profile');
    });

    Route::group(['prefix' => 'account', 'as' => 'account.'], function () {
        Route::get('change-password', ForceChangePassword::class)->name('force-change-password');
        Route::get('activate-two-factor', ForceActivateTwoFactor::class)->name('force-activate-two-factor');
    });
});

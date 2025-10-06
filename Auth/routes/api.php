<?php

use Illuminate\Support\Facades\Route;
use Modules\Auth\Http\Controllers\AccountController;
use Modules\Auth\Http\Controllers\AuthController;
use Modules\Auth\Http\Controllers\UnsplashController;

/*
 *--------------------------------------------------------------------------
 * API Routes
 *--------------------------------------------------------------------------
 *
 * Here is where you can register API routes for your application. These
 * routes are loaded by the RouteServiceProvider within a group which
 * is assigned the "api" middleware group. Enjoy building your API!
 *
*/

Route::group(['middleware' => ['api_key'], 'prefix' => 'v1', 'name' => 'api.'], function () {
    Route::get('/', fn() => response()->json(['message' => 'Welcome to the API! Docs can be found under /docs/api']));

    Route::group(['prefix' => 'account'], function () {
        Route::get('/', [AccountController::class, 'getUser'])->name('account.user');
        Route::delete('/', [AccountController::class, 'deleteAccount'])->name('account.delete');
        Route::put('/', [AccountController::class, 'updateAccount'])->name('account.update');
        Route::get('permission', [AccountController::class, 'hasPermission'])->name('account.user.permission');
        Route::get('activity', [AccountController::class, 'getActivity'])->name('account.activity');
        Route::get('sessions', [AccountController::class, 'getSessions'])->name('account.sessions');
        Route::post('avatar', [AccountController::class, 'uploadAvatar'])->name('account.avatar.upload');

        Route::group(['prefix' => 'twofa'], function () {
            Route::post('enable', [AccountController::class, 'activateTwoFactor'])->name('account.two-fa.enable');
            Route::post('disable', [AccountController::class, 'disableTwoFactor'])->name('account.two-fa.disable');
            Route::post('verify', [AccountController::class, 'verifyTwoFactorCode'])->name('account.two-fa.verify');
            Route::post('regenerate', [AccountController::class, 'regenerateRecoveryCodes'])->name('account.two-fa.regenerate');
        });
    });

    Route::group(['prefix' => 'auth'], function () {
        Route::post('logout', [AuthController::class, 'logout'])->name('auth.logout');
    });
});

Route::group(['prefix' => 'v1/auth', 'name' => 'api.'], function () {
    Route::get('lookup/{username}', [AuthController::class, 'lookupUser'])->name('auth.lookup')->middleware('throttle:10,1');
    Route::post('login', [AuthController::class, 'login'])->name('auth.login')->middleware('throttle:10,1');
    Route::post('register', [AuthController::class, 'register'])->name('auth.register')->middleware('throttle:5,1');
});

Route::group(['prefix' => 'v1/unsplash', 'name' => 'api.'], function () {
    Route::get('css', [UnsplashController::class, 'getRandomBackgroundCss'])->name('unsplash.css')->middleware('throttle:50,1');
    Route::get('url', [UnsplashController::class, 'getRandomBackgroundUrl'])->name('unsplash.url')->middleware('throttle:50,1');
    Route::get('utm', [UnsplashController::class, 'getUtmSource'])->name('unsplash.utm')->middleware('throttle:50,1');
});

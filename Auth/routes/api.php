<?php

declare(strict_types=1);

use Modules\Auth\Http\Controllers\API\V1\AuthController;
use Modules\Auth\Http\Controllers\API\V1\OAuthProvidersController;
use Modules\Auth\Http\Controllers\API\V1\UnsplashController;

Route::group(['prefix' => 'v1/auth', 'as' => 'auth.'], function () {
    Route::group(['middleware' => 'throttle:10,1'], function () {
        Route::get('lookup/{username}', [AuthController::class, 'lookupUser'])->name('lookup');

        Route::post('register', [AuthController::class, 'register'])->name('register');
        Route::post('logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth:sanctum');

        Route::post('forgot-password', [AuthController::class, 'forgotPassword'])->name('forgot-password');
        Route::post('forgot-password/{resetToken}', [AuthController::class, 'forgotPassword'])->name('forgot-password');

        Route::post('login', [AuthController::class, 'login'])->name('login');
        Route::post('login/qrcode/{userId}', [AuthController::class, 'loginQrCode'])->middleware('signed')->name('login.qrcode');
    });

    Route::group(['prefix' => 'unsplash', 'middleware' => 'throttle:60,1'], function () {
        Route::get('css', [UnsplashController::class, 'getBackgroundCSS'])->name('unsplash.background.css');
        Route::get('image', [UnsplashController::class, 'getBackgroundImage'])->name('unsplash.background.image');
        Route::get('utm', [UnsplashController::class, 'getUTM'])->name('unsplash.utm');
    });

    Route::get('captcha/{config?}', [AuthController::class, 'captcha'])->middleware(['throttle:60,1'])->name('captcha');

    Route::group(['prefix' => 'oauth-providers', 'as' => 'oauth-providers.', 'middleware' => ['auth:sanctum', 'api_force_actions']], function () {
        Route::get('/', [OAuthProvidersController::class, 'getOAuthProviders'])->name('all')->can('admin.settings');
        Route::get('{oauthProviderId}', [OAuthProvidersController::class, 'getOAuthProvider'])->name('get')->can('admin.settings');
        Route::post('/', [OAuthProvidersController::class, 'createOAuthProvider'])->name('create')->can('admin.settings');
        Route::patch('{oauthProviderId}', [OAuthProvidersController::class, 'updateOAuthProvider'])->name('update')->can('admin.settings');
        Route::delete('{oauthProviderId}', [OAuthProvidersController::class, 'deleteOAuthProvider'])->name('delete')->can('admin.settings');
    });
});

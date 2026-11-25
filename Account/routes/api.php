<?php

declare(strict_types=1);

use Modules\Account\Http\Controllers\API\V1\ActivityController;
use Modules\Account\Http\Controllers\API\V1\APIKeysController;
use Modules\Account\Http\Controllers\API\V1\MFAController;
use Modules\Account\Http\Controllers\API\V1\ProfileController;
use Modules\Account\Http\Controllers\API\V1\SessionsController;
use Modules\Account\Http\Controllers\API\V1\SettingsController;

Route::group(['prefix' => 'v1/account', 'as' => 'account.', 'middleware' => ['auth:sanctum', 'api_force_actions']], function () {
    Route::group(['prefix' => 'profile', 'as' => 'profile.'], function () {
        Route::patch('/', [ProfileController::class, 'updateProfile'])->name('update');
        Route::delete('/', [ProfileController::class, 'deleteAccount'])->name('delete');
        Route::patch('password', [ProfileController::class, 'updatePassword'])->name('password.update')->withoutMiddleware('api_force_actions');

        Route::put('avatar', [ProfileController::class, 'changeAvatar'])->name('avatar.update');
        Route::delete('avatar', [ProfileController::class, 'resetAvatar'])->name('avatar.reset');
    });
    Route::group(['prefix' => 'sessions', 'as' => 'sessions.'], function () {
        Route::post('qrcode', [SessionsController::class, 'getLoginQrCode'])->name('qrcode');
        Route::get('web', [SessionsController::class, 'getWebSessions'])->name('web');
        Route::get('native', [SessionsController::class, 'getNativeSessions'])->name('native');
        Route::delete('web/{sessionId}', [SessionsController::class, 'revokeWebSession'])->name('web.revoke');
        Route::delete('native/{sessionId}', [SessionsController::class, 'revokeNativeSession'])->name('native.revoke');
    });
    Route::group(['prefix' => 'mfa', 'as' => 'mfa.'], function () {
        Route::post('secret', [MFAController::class, 'generateMFASecret'])->name('secret');
        Route::post('enable', [MFAController::class, 'enableMFA'])->name('enable');
        Route::post('disable', [MFAController::class, 'disableMFA'])->name('disable');
        Route::post('codes', [MFAController::class, 'regenerateRecoveryCodes'])->name('codes');
        Route::post('verify', [MFAController::class, 'verifyMFA'])->name('verify');
    })->withoutMiddleware('api_force_actions');
    Route::group(['prefix' => 'api', 'as' => 'api.'], function () {
        Route::get('/', [APIKeysController::class, 'getApiKeys'])->name('get');
        Route::post('/', [APIKeysController::class, 'createApiKey'])->name('create');
        Route::delete('{apiKeyId}', [APIKeysController::class, 'deleteApiKey'])->name('delete');
    });
    Route::group(['prefix' => 'settings', 'as' => 'settings.'], function () {
        Route::get('/', [SettingsController::class, 'getSettings'])->name('get');
        Route::post('/', [SettingsController::class, 'updateSettings'])->name('update');
        Route::delete('{settingId}', [SettingsController::class, 'deleteSetting'])->name('delete');
    });
    Route::get('activities', [ActivityController::class, 'getActivities'])->name('activities');
});

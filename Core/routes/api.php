<?php

declare(strict_types=1);

use Modules\Core\Http\Controllers\API\SettingsController;
use Modules\Core\Http\Controllers\API\UserController;

Route::group(['prefix' => 'v1/core', 'as' => 'core.'], function () {
    Route::group(['middleware' => ['throttle:60,1']], function () {
        Route::get('settings/{key}', [SettingsController::class, 'getSetting'])->name('settings.get');
        Route::get('user-settings/{key}', [SettingsController::class, 'getUserSetting'])->middleware('auth:sanctum')->name('user.settings.get');
    });

    Route::group(['middleware' => ['auth:sanctum']], function () {
        Route::get('users/me', [UserController::class, 'me'])->name('users.me');

        Route::group(['prefix' => 'users/me'], function () {
            Route::get('roles', [UserController::class, 'roles'])->name('users.me.roles');
            Route::get('permissions', [UserController::class, 'permissions'])->name('users.me.permissions');
            Route::get('settings', [UserController::class, 'settings'])->name('users.me.settings');
        });
    });
});

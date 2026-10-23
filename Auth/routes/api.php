<?php

declare(strict_types=1);

use Modules\Auth\Http\Controllers\API\AuthController;

Route::group(['prefix' => 'v1/auth', 'as' => 'auth.'], function () {
    Route::group(['middleware' => 'throttle:10,1'], function () {
        Route::post('register', [AuthController::class, 'register'])->name('register');
        Route::post('logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth:sanctum');

        Route::post('forgot-password', [AuthController::class, 'forgotPassword'])->name('forgot-password');
        Route::post('forgot-password/{resetToken}', [AuthController::class, 'forgotPassword'])->name('forgot-password');

        Route::post('login', [AuthController::class, 'login'])->name('login');
        Route::post('login/qrcode/{userId}', [AuthController::class, 'loginQrCode'])->middleware('signed')->name('login.qrcode');
    });

    Route::get('captcha/{config?}', [AuthController::class, 'captcha'])->middleware(['throttle:60,1'])->name('captcha');
});

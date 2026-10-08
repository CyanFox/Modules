<?php

declare(strict_types=1);

Route::group(['prefix' => 'v1/auth', 'as' => 'auth.'], function () {
    Route::group(['prefix' => 'login', 'as' => 'login.'], function () {
        Route::get('qrcode/{userId}', fn() => apiResponse('QR-Code Login'))->middleware('signed')->name('qrcode');
    });
});

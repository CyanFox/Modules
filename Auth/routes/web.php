<?php

declare(strict_types=1);

Route::group(['prefix' => 'auth', 'as' => 'auth.'], function () {
    Route::group(['middleware' => ['guest', 'throttle:20,1']], function () {
        Route::livewire('login', 'auth::login')->name('login');
        Route::livewire('forgot-password', 'auth::forgot-password')->name('forgot-password');
        Route::livewire('register', 'auth::register')->name('register');
    });

    Route::group(['middleware' => ['auth']], function () {
        Route::get('logout', function () {
            auth()->logout();

            return redirect()->route('auth.login');
        })->name('logout');
    });
});

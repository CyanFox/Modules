<?php declare(strict_types=1);

Route::group(['middleware' => ['auth', 'web']], function () {
    Route::livewire('dashboard', 'dashboard::dashboard')->name('dashboard');
    Route::get('/', fn() => redirect()->route('dashboard'));
});

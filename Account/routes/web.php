<?php declare(strict_types=1);

use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'account', 'as' => 'account.', 'middleware' => ['auth', 'web']], function () {
    Route::livewire('profile', 'account::profile')->name('profile');
    Route::livewire('sessions', 'account::sessions')->name('sessions');
    Route::livewire('activity', 'account::activity')->name('activity');
    Route::livewire('api', 'account::api-keys')->name('api');
});

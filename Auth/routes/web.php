<?php

declare(strict_types=1);

Route::group(['prefix' => 'auth', 'as' => 'auth.', 'middleware' => ['guest', 'web']], function () {
    Route::livewire('login', 'auth::auth.login')->name('login');
});

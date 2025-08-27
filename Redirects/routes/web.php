<?php

use Illuminate\Support\Facades\Route;
use Modules\Redirects\Livewire\CreateRedirect;
use Modules\Redirects\Livewire\Redirects;
use Modules\Redirects\Livewire\UpdateRedirect;

Route::group(['prefix' => 'redirects', 'middleware' => ['auth']], function () {
    Route::get('/', Redirects::class)->name('redirects');
    Route::get('create', CreateRedirect::class)->name('redirects.create');
    Route::get('update/{redirectId}', UpdateRedirect::class)->name('redirects.update');
});

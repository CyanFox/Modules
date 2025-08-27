<?php

use Modules\Redirects\Http\Controllers\RedirectsController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['api_key'], 'prefix' => 'v1/redirects'], function () {
    Route::prefix('admin')->group(function () {
        Route::get('/', [RedirectsController::class, 'getRedirects'])->name('Get Redirects');
        Route::post('/', [RedirectsController::class, 'createRedirect'])->name('Create Redirect');
        Route::get('{redirectId}', [RedirectsController::class, 'getRedirect'])->name('Get specific Redirect');
        Route::put('{redirectId}', [RedirectsController::class, 'updateRedirect'])->name('Update Redirect');
        Route::delete('{redirectId}', [RedirectsController::class, 'deleteRedirect'])->name('Delete Redirect');
        Route::get('{redirectId}/stats', [RedirectsController::class, 'getRedirectStats'])->name('Get Redirect Stats');
    });
});

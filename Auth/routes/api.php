<?php

use Illuminate\Support\Facades\Route;
use Modules\Auth\Http\Controllers\AccountController;

/*
 *--------------------------------------------------------------------------
 * API Routes
 *--------------------------------------------------------------------------
 *
 * Here is where you can register API routes for your application. These
 * routes are loaded by the RouteServiceProvider within a group which
 * is assigned the "api" middleware group. Enjoy building your API!
 *
*/

Route::middleware(['api_key'])->prefix('v1')->group(function () {
    Route::get('/', fn () => response()->json(['message' => 'Welcome to the API! Docs can be found under /docs/api']));

    Route::group(['prefix' => 'account'], function () {
        Route::get('/', [AccountController::class, 'getUser'])->name('Get User');
        Route::put('update', [AccountController::class, 'updateAccount'])->name('Update Account');
        Route::get('activity', [AccountController::class, 'getActivity'])->name('Get Activity');
        Route::get('sessions', [AccountController::class, 'getSessions'])->name('Get Sessions');
    });
});

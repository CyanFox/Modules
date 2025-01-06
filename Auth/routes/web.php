<?php

use Illuminate\Support\Facades\Route;
use Modules\Auth\Livewire\Account\Profile;
use Modules\Auth\Livewire\Auth\ForgotPassword;
use Modules\Auth\Livewire\Auth\Login;
use Modules\Auth\Livewire\Auth\Register;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::group(['prefix' => 'auth', 'as' => 'auth.'], function () {
    Route::group(['middleware' => ['guest', 'throttle:10,1', 'language']], function () {
        Route::get('login', Login::class)->name('login');
        Route::get('register', Register::class)->name('register');
        Route::get('forgot-password', ForgotPassword::class)->name('forgot-password');
    });

    Route::group(['middleware' => ['auth', 'language']], function () {
        Route::get('logout', function () {
            auth()->logout();

            return redirect()->route('auth.login');
        })->name('logout');
    });
});

Route::group(['prefix' => 'account', 'as' => 'account.', 'middleware' => ['auth', 'language']], function () {
    Route::get('profile', Profile::class)->name('profile');
});

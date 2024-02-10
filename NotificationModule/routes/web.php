<?php

use Illuminate\Support\Facades\Route;
use Modules\NotificationModule\app\Livewire\Admin\Notifications\CreateNotification;
use Modules\NotificationModule\app\Livewire\Admin\Notifications\Notifications;
use Modules\NotificationModule\app\Livewire\Admin\Notifications\UpdateNotification;

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

Route::group(['prefix' => 'admin', 'middleware' => ['role:Super Admin', 'auth', 'disabled']], function () {

    Route::group(['prefix' => 'notifications'], function () {
        Route::get('/', Notifications::class)->name('admin.notifications');
        Route::get('/create', CreateNotification::class)->name('admin.notifications.create');
        Route::get('/update/{notificationId}', UpdateNotification::class)->name('admin.notifications.update');
    });
});

Route::group(['middleware' => ['auth', 'disabled', 'force_change']], function () {
    Route::group(['prefix' => 'account'], function () {
        Route::get('notifications', \Modules\NotificationModule\app\Livewire\Account\Notifications::class)->name('account.notifications');
    });
});

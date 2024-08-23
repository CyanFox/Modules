<?php

use Illuminate\Support\Facades\Route;
use Modules\NotificationModule\Livewire\Admin\CreateNotification;
use Modules\NotificationModule\Livewire\Admin\Notifications;
use Modules\NotificationModule\Livewire\Admin\UpdateNotification;

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

Route::group(['prefix' => 'admin/notifications', 'middleware' => ['auth', 'can:adminmodule.admin', 'language', 'disabled'], 'as' => 'admin.'], function () {
    Route::get('/', Notifications::class)->name('notifications')->can('notificationmodule.notifications.admin.view');
    Route::get('create', CreateNotification::class)->name('notifications.create')->can('notificationmodule.notifications.admin.create');
    Route::get('update/{notificationId}', UpdateNotification::class)->name('notifications.update')->can('notificationmodule.notifications.admin.update');
});

Route::group(['prefix' => 'notifications', 'middleware' => ['auth', 'language', 'disabled']], function () {
    Route::get('/', \Modules\NotificationModule\Livewire\Notifications::class)->name('notifications');
});

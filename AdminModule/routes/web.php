<?php

use Illuminate\Support\Facades\Route;
use Modules\AdminModule\Livewire\AdminDashboard;
use Modules\AdminModule\Livewire\Groups;
use Modules\AdminModule\Livewire\Users;

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

Route::group(['middleware' => ['auth', 'role:Super Admin'], 'prefix' => 'admin', 'as' => 'admin.'], function () {
    Route::get('/', AdminDashboard::class)->name('dashboard');
    Route::get('/users', Users::class)->name('users');
    Route::get('/groups', Groups::class)->name('groups');
    Route::get('/settings', AdminDashboard::class)->name('settings');
    Route::get('/modules', AdminDashboard::class)->name('modules');
});

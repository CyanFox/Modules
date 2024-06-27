<?php

use Illuminate\Support\Facades\Route;
use Modules\AdminModule\Livewire\Dashboard;
use Modules\AdminModule\Livewire\Groups;
use Modules\AdminModule\Livewire\Modules;
use Modules\AdminModule\Livewire\Permissions;
use Modules\AdminModule\Livewire\Settings;
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

Route::group(['middleware' => ['auth', 'can:adminmodule.admin'], 'prefix' => 'admin', 'as' => 'admin.', 'domain' => setting('adminmodule.domains.admin')], function () {
    Route::get('/', Dashboard::class)->name('dashboard')->middleware('can:adminmodule.dashboard.view');
    Route::get('/users', Users::class)->name('users')->middleware('can:adminmodule.users.view');
    Route::get('/groups', Groups::class)->name('groups')->middleware('can:adminmodule.groups.view');
    Route::get('/permissions', Permissions::class)->name('permissions')->middleware('can:adminmodule.permissions.view');
    Route::get('/settings', Settings::class)->name('settings')->middleware('can:adminmodule.settings.view');
    Route::get('/modules', Modules::class)->name('modules')->middleware('can:adminmodule.modules.view');
});

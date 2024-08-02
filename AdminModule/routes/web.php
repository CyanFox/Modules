<?php

use Illuminate\Support\Facades\Route;
use Modules\AdminModule\Livewire\Dashboard;
use Modules\AdminModule\Livewire\Groups\CreateGroup;
use Modules\AdminModule\Livewire\Groups\Groups;
use Modules\AdminModule\Livewire\Groups\UpdateGroup;
use Modules\AdminModule\Livewire\Modules;
use Modules\AdminModule\Livewire\Permissions\CreatePermission;
use Modules\AdminModule\Livewire\Permissions\Permissions;
use Modules\AdminModule\Livewire\Permissions\UpdatePermission;
use Modules\AdminModule\Livewire\Settings;
use Modules\AdminModule\Livewire\Users\CreateUser;
use Modules\AdminModule\Livewire\Users\UpdateUser;
use Modules\AdminModule\Livewire\Users\Users;

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

Route::group(['middleware' => ['auth', 'can:adminmodule.admin', 'language', 'disabled'], 'prefix' => 'admin', 'as' => 'admin.', 'domain' => setting('adminmodule.domains.admin')], function () {
    Route::get('/', Dashboard::class)->name('dashboard')->middleware('can:adminmodule.dashboard.view');

    Route::prefix('users')->group(function () {
        Route::get('/', Users::class)->name('users')->middleware('can:adminmodule.users.view');
        Route::get('/create', CreateUser::class)->name('users.create')->middleware('can:adminmodule.users.create');
        Route::get('/update/{userId}', UpdateUser::class)->name('users.update')->middleware('can:adminmodule.users.update');
    });

    Route::prefix('groups')->group(function () {
        Route::get('/', Groups::class)->name('groups')->middleware('can:adminmodule.groups.view');
        Route::get('/create', CreateGroup::class)->name('groups.create')->middleware('can:adminmodule.groups.create');
        Route::get('/update/{groupId}', UpdateGroup::class)->name('groups.update')->middleware('can:adminmodule.groups.update');
    });

    Route::prefix('permissions')->group(function () {
        Route::get('/', Permissions::class)->name('permissions')->middleware('can:adminmodule.permissions.view');
        Route::get('/create', CreatePermission::class)->name('permissions.create')->middleware('can:adminmodule.permissions.create');
        Route::get('/update/{permissionId}', UpdatePermission::class)->name('permissions.update')->middleware('can:adminmodule.permissions.update');
    });

    Route::get('/settings', Settings::class)->name('settings')->middleware('can:adminmodule.settings.view');
    Route::get('/modules', Modules::class)->name('modules')->middleware('can:adminmodule.modules.view');
});

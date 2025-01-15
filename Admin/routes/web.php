<?php

use Illuminate\Support\Facades\Route;
use Modules\Admin\Livewire\Dashboard;
use Modules\Admin\Livewire\Groups\CreateGroup;
use Modules\Admin\Livewire\Groups\Groups;
use Modules\Admin\Livewire\Groups\UpdateGroup;
use Modules\Admin\Livewire\Permissions\CreatePermission;
use Modules\Admin\Livewire\Permissions\Permissions;
use Modules\Admin\Livewire\Permissions\UpdatePermission;
use Modules\Admin\Livewire\Users\CreateUser;
use Modules\Admin\Livewire\Users\UpdateUser;
use Modules\Admin\Livewire\Users\Users;

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

Route::group(['prefix' => 'admin', 'as' => 'admin.', 'middleware' => ['auth', 'role:Super Admin']], function () {
    Route::get('dashboard', Dashboard::class)->name('dashboard');

    Route::group(['prefix' => 'users'], function () {
        Route::get('/', Users::class)->name('users');
        Route::get('create', CreateUser::class)->name('users.create');
        Route::get('update/{userId}', UpdateUser::class)->name('users.update');
    });

    Route::group(['prefix' => 'groups'], function () {
        Route::get('/', Groups::class)->name('groups');
        Route::get('create', CreateGroup::class)->name('groups.create');
        Route::get('update/{groupId}', UpdateGroup::class)->name('groups.update');
    });

    Route::group(['prefix' => 'permissions'], function () {
        Route::get('/', Permissions::class)->name('permissions');
        Route::get('create', CreatePermission::class)->name('permissions.create');
        Route::get('update/{permissionId}', UpdatePermission::class)->name('permissions.update');
    });

    Route::group(['prefix' => 'settings'], function () {
        Route::get('/', Users::class)->name('settings');
    });
});

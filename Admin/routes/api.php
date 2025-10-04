<?php

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

use Illuminate\Support\Facades\Route;
use Modules\Admin\Http\Controllers\AdminActivityController;
use Modules\Admin\Http\Controllers\AdminGroupsController;
use Modules\Admin\Http\Controllers\AdminModuleController;
use Modules\Admin\Http\Controllers\AdminPermissionsController;
use Modules\Admin\Http\Controllers\AdminSettingsController;
use Modules\Admin\Http\Controllers\AdminUsersController;
use Modules\Admin\Http\Controllers\AdminVersionController;

Route::group(['middleware' => ['api_key'], 'prefix' => 'v1/admin'], function () {
    Route::get('activity', [AdminActivityController::class, 'getActivity'])->name('admin.activity');
    Route::group(['prefix' => 'versions'], function () {
        Route::group(['prefix' => 'current'], function () {
            Route::get('base', [AdminVersionController::class, 'getCurrentBaseVersion'])->name('admin.versions.base.current');
            Route::get('module', [AdminVersionController::class, 'getCurrentModuleVersion'])->name('admin.versions.module.current');
        });
        Route::group(['prefix' => 'remote'], function () {
            Route::get('base', [AdminVersionController::class, 'getRemoteBaseVersion'])->name('admin.versions.base.remote');
            Route::get('module', [AdminVersionController::class, 'getRemoteModuleVersion'])->name('admin.versions.module.remote');
        });
        Route::group(['prefix' => 'status'], function () {
            Route::get('base', [AdminVersionController::class, 'isBaseUpToDate'])->name('admin.versions.base.status');
            Route::get('module', [AdminVersionController::class, 'isModuleUpToDate'])->name('admin.versions.module.status');
        });
    });

    Route::group(['prefix' => 'users'], function () {
        Route::get('/', [AdminUsersController::class, 'getUsers'])->name('admin.users');
        Route::post('/', [AdminUsersController::class, 'createUser'])->name('admin.users.create');
        Route::get('{userId}', [AdminUsersController::class, 'getUser'])->name('admin.users.view');
        Route::put('{userId}', [AdminUsersController::class, 'updateUser'])->name('admin.users.update');
        Route::delete('{userId}', [AdminUsersController::class, 'deleteUser'])->name('admin.users.delete');
    });

    Route::group(['prefix' => 'groups'], function () {
        Route::get('/', [AdminGroupsController::class, 'getGroups'])->name('admin.groups');
        Route::post('/', [AdminGroupsController::class, 'createGroup'])->name('admin.groups.create');
        Route::get('{groupId}', [AdminGroupsController::class, 'getGroup'])->name('admin.groups.view');
        Route::put('{groupId}', [AdminGroupsController::class, 'updateGroup'])->name('admin.groups.update');
        Route::delete('{groupId}', [AdminGroupsController::class, 'deleteGroup'])->name('admin.groups.delete');
    });

    Route::group(['prefix' => 'permissions'], function () {
        Route::get('/', [AdminPermissionsController::class, 'getPermissions'])->name('admin.permissions');
        Route::post('/', [AdminPermissionsController::class, 'createPermission'])->name('admin.permissions.create');
        Route::get('{permissionId}', [AdminPermissionsController::class, 'getPermission'])->name('admin.permissions.view');
        Route::put('{permissionId}', [AdminPermissionsController::class, 'updatePermission'])->name('admin.permissions.update');
        Route::delete('{permissionId}', [AdminPermissionsController::class, 'deletePermission'])->name('admin.permissions.delete');
    });

    Route::group(['prefix' => 'settings'], function () {
        Route::get('/', [AdminSettingsController::class, 'getSettings'])->name('admin.settings');
        Route::post('/', [AdminSettingsController::class, 'createSetting'])->name('admin.settings.create');
        Route::post('encrypt', [AdminSettingsController::class, 'encryptValue'])->name('admin.settings.encrypt');
        Route::post('decrypt', [AdminSettingsController::class, 'decryptValue'])->name('admin.settings.decrypt');
        Route::get('{settingKey}', [AdminSettingsController::class, 'getSetting'])->name('admin.settings.view');
        Route::put('{settingKey}', [AdminSettingsController::class, 'updateSetting'])->name('admin.settings.update');
        Route::delete('{settingKey}', [AdminSettingsController::class, 'deleteSetting'])->name('admin.settings.delete');
    });

    Route::group(['prefix' => 'modules'], function () {
        Route::get('/', [AdminModuleController::class, 'getModules'])->name('admin.modules');
        Route::get('{moduleName}', [AdminModuleController::class, 'getModule'])->name('admin.modules.view');
        Route::patch('{moduleName}/disable', [AdminModuleController::class, 'disableModule'])->name('admin.modules.disable');
        Route::patch('{moduleName}/enable', [AdminModuleController::class, 'enableModule'])->name('admin.modules.enable');
        Route::delete('{moduleName}', [AdminModuleController::class, 'deleteModule'])->name('admin.modules.delete');

        Route::group(['prefix' => '{moduleName}/version'], function () {
            Route::get('current', [AdminModuleController::class, 'getModuleVersion'])->name('admin.modules.version.current');
            Route::get('remote', [AdminModuleController::class, 'getRemoteModuleVersion'])->name('admin.modules.version.remote');
            Route::get('status', [AdminModuleController::class, 'isModuleUpToDate'])->name('admin.modules.version.status');
        });
    });
});

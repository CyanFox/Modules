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
    Route::get('activity', [AdminActivityController::class, 'getActivity'])->name('Get Activity');
    Route::group(['prefix' => 'versions'], function () {
        Route::group(['prefix' => 'current'], function () {
            Route::get('base', [AdminVersionController::class, 'getCurrentBaseVersion'])->name('Get Current Base Version');
            Route::get('module', [AdminVersionController::class, 'getCurrentModuleVersion'])->name('Get Current Module Version');
        });
        Route::group(['prefix' => 'remote'], function () {
            Route::get('base', [AdminVersionController::class, 'getRemoteBaseVersion'])->name('Get Remote Base Version');
            Route::get('module', [AdminVersionController::class, 'getRemoteModuleVersion'])->name('Get Remote Module Version');
        });
        Route::group(['prefix' => 'status'], function () {
            Route::get('base', [AdminVersionController::class, 'isBaseUpToDate'])->name('Is Base Up To Date');
            Route::get('module', [AdminVersionController::class, 'isModuleUpToDate'])->name('Is Module Up To Date');
        });
    });

    Route::group(['prefix' => 'users'], function () {
        Route::get('/', [AdminUsersController::class, 'getUsers'])->name('Get Users');
        Route::post('/', [AdminUsersController::class, 'createUser'])->name('Create User');
        Route::get('{userId}', [AdminUsersController::class, 'getUser'])->name('Get specific User');
        Route::put('{userId}', [AdminUsersController::class, 'updateUser'])->name('Update User');
        Route::delete('{userId}', [AdminUsersController::class, 'deleteUser'])->name('Delete User');
    });

    Route::group(['prefix' => 'groups'], function () {
        Route::get('/', [AdminGroupsController::class, 'getGroups'])->name('Get Groups');
        Route::post('/', [AdminGroupsController::class, 'createGroup'])->name('Create Group');
        Route::get('{groupId}', [AdminGroupsController::class, 'getGroup'])->name('Get specific Group');
        Route::put('{groupId}', [AdminGroupsController::class, 'updateGroup'])->name('Update Group');
        Route::delete('{groupId}', [AdminGroupsController::class, 'deleteGroup'])->name('Delete Group');
    });

    Route::group(['prefix' => 'permissions'], function () {
        Route::get('/', [AdminPermissionsController::class, 'getPermissions'])->name('Get Permissions');
        Route::post('/', [AdminPermissionsController::class, 'createPermission'])->name('Create Permission');
        Route::get('{permissionId}', [AdminPermissionsController::class, 'getPermission'])->name('Get specific Permission');
        Route::put('{permissionId}', [AdminPermissionsController::class, 'updatePermission'])->name('Update Permission');
        Route::delete('{permissionId}', [AdminPermissionsController::class, 'deletePermission'])->name('Delete Permission');
    });

    Route::group(['prefix' => 'settings'], function () {
        Route::get('/', [AdminSettingsController::class, 'getSettings'])->name('Get Settings');
        Route::post('/', [AdminSettingsController::class, 'createSetting'])->name('Create Setting');
        Route::post('encrypt', [AdminSettingsController::class, 'encryptValue'])->name('Encrypt Value');
        Route::post('decrypt', [AdminSettingsController::class, 'decryptValue'])->name('Decrypt Value');
        Route::get('{settingKey}', [AdminSettingsController::class, 'getSetting'])->name('Get specific Setting');
        Route::put('{settingKey}', [AdminSettingsController::class, 'updateSetting'])->name('Update Setting');
        Route::delete('{settingKey}', [AdminSettingsController::class, 'deleteSetting'])->name('Delete Setting');
    });

    Route::group(['prefix' => 'modules'], function () {
        Route::get('/', [AdminModuleController::class, 'getModules'])->name('Get Modules');
        Route::get('{moduleName}', [AdminModuleController::class, 'getModule'])->name('Get specific Module');
        Route::patch('{moduleName}/disable', [AdminModuleController::class, 'disableModule'])->name('Disable Module');
        Route::patch('{moduleName}/enable', [AdminModuleController::class, 'enableModule'])->name('Enable Module');
        Route::delete('{moduleName}', [AdminModuleController::class, 'deleteModule'])->name('Delete Module');

        Route::group(['prefix' => '{moduleName}/version'], function () {
            Route::get('current', [AdminModuleController::class, 'getModuleVersion'])->name('Get Current Module Version');
            Route::get('remote', [AdminModuleController::class, 'getRemoteModuleVersion'])->name('Get Remote Module Version');
            Route::get('status', [AdminModuleController::class, 'isModuleUpToDate'])->name('Is Module Up To Date');
        });
    });
});

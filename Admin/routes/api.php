<?php declare(strict_types=1);

use Modules\Admin\Http\Controllers\API\V1\ActivityController;
use Modules\Admin\Http\Controllers\API\V1\ModulesController;
use Modules\Admin\Http\Controllers\API\V1\PermissionsController;
use Modules\Admin\Http\Controllers\API\V1\RolesController;
use Modules\Admin\Http\Controllers\API\V1\SettingsController;
use Modules\Admin\Http\Controllers\API\V1\UsersController;

Route::group(['prefix' => 'v1/admin', 'as' => 'admin.', 'middleware' => ['auth:sanctum']], function () {
    Route::group(['prefix' => 'users', 'as' => 'users.'], function () {
        Route::get('/', [UsersController::class, 'getUsers'])->name('all')->can('admin.users');
        Route::get('{userId}', [UsersController::class, 'getUser'])->name('get')->can('admin.users');
        Route::post('/', [UsersController::class, 'createUser'])->name('create')->can('admin.users.create');
        Route::patch('{userId}', [UsersController::class, 'updateUser'])->name('update')->can('admin.users.update');
        Route::delete('{userId}', [UsersController::class, 'deleteUser'])->name('delete')->can('admin.users.delete');
    });
    Route::group(['prefix' => 'roles', 'as' => 'roles.'], function () {
        Route::get('/', [RolesController::class, 'getRoles'])->name('all')->can('admin.roles');
        Route::get('{roleId}', [RolesController::class, 'getRole'])->name('get')->can('admin.roles');
        Route::post('/', [RolesController::class, 'createRole'])->name('create')->can('admin.roles.create');
        Route::patch('{roleId}', [RolesController::class, 'updateRole'])->name('update')->can('admin.roles.update');
        Route::delete('{roleId}', [RolesController::class, 'deleteRole'])->name('delete')->can('admin.roles.delete');
    });
    Route::group(['prefix' => 'permissions', 'as' => 'permission.'], function () {
        Route::get('/', [PermissionsController::class, 'getPermissions'])->name('all')->can('admin.permissions');
        Route::get('{permissionId}', [PermissionsController::class, 'getPermission'])->name('get')->can('admin.permissions');
        Route::post('/', [PermissionsController::class, 'createPermission'])->name('create')->can('admin.permissions.create');
        Route::patch('{permissionId}', [PermissionsController::class, 'updatePermission'])->name('update')->can('admin.permissions.update');
        Route::delete('{permissionId}', [PermissionsController::class, 'deletePermission'])->name('delete')->can('admin.permissions.delete');
    });
    Route::group(['prefix' => 'modules', 'as' => 'modules.'], function () {
        Route::get('/', [ModulesController::class, 'getModules'])->name('all')->can('admin.modules');
        Route::get('{module}', [ModulesController::class, 'getModule'])->name('get')->can('admin.modules');
        Route::post('{module}/disable', [ModulesController::class, 'disableModule'])->name('create')->can('admin.modules.disable');
        Route::post('{module}/enable', [ModulesController::class, 'enableModule'])->name('create')->can('admin.modules.enable');
        Route::delete('{module}', [ModulesController::class, 'deleteModule'])->name('delete')->can('admin.modules.delete');
    });
    Route::group(['prefix' => 'settings', 'as' => 'settings.'], function () {
        Route::get('/', [SettingsController::class, 'getSettings'])->name('all')->can('admin.settings');
        Route::patch('/', [SettingsController::class, 'updateSettings'])->name('update.all')->can('admin.settings');
        Route::post('/', [SettingsController::class, 'createSetting'])->name('create')->can('admin.settings');

        Route::put('logo', [SettingsController::class, 'changeLogo'])->name('logo.change')->can('admin.settings');
        Route::delete('logo', [SettingsController::class, 'resetLogo'])->name('logo.reset')->can('admin.settings');

        Route::get('{key}', [SettingsController::class, 'getSetting'])->name('get')->can('admin.settings');
        Route::patch('{key}', [SettingsController::class, 'updateSetting'])->name('update')->can('admin.settings');
        Route::delete('{key}', [SettingsController::class, 'deleteSetting'])->name('delete')->can('admin.settings');
    });

    Route::get('activities', [ActivityController::class, 'getActivities'])->name('activities')->can('admin.activity');
});

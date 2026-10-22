<?php declare(strict_types=1);

Route::group(['prefix' => 'admin', 'as' => 'admin.', 'middleware' => ['auth', 'web']], function () {
    Route::livewire('dashboard', 'admin::dashboard')->name('dashboard')->can('admin.dashboard');
    Route::livewire('modules', 'admin::modules')->name('modules')->can('admin.modules');
    Route::livewire('activity', 'admin::activity')->name('activity')->can('admin.activity');

    Route::group(['prefix' => 'settings'], function () {
        Route::livewire('/', 'admin::settings.general')->name('settings')->can('admin.settings');
        Route::livewire('module', 'admin::settings.module')->name('settings.module')->can('admin.settings');
        Route::livewire('editor', 'admin::settings.editor')->name('settings.editor')->can('admin.settings');
    });

    Route::group(['prefix' => 'users'], function () {
        Route::livewire('/', 'admin::users.list')->name('users')->can('admin.users');
        Route::livewire('create', 'admin::users.create')->name('users.create')->can('admin.users.create');
        Route::livewire('update/{userId}', 'admin::users.update')->name('users.update')->can('admin.users.update');
    });

    Route::group(['prefix' => 'roles'], function () {
        Route::livewire('/', 'admin::roles.list')->name('roles')->can('admin.roles');
        Route::livewire('create', 'admin::roles.create')->name('roles.create')->can('admin.roles.create');
        Route::livewire('update/{roleId}', 'admin::roles.update')->name('roles.update')->can('admin.roles.update');
    });

    Route::group(['prefix' => 'permissions'], function () {
        Route::livewire('/', 'admin::permissions.list')->name('permissions')->can('admin.permissions');
        Route::livewire('create', 'admin::permissions.create')->name('permissions.create')->can('admin.permissions.create');
        Route::livewire('update/{permissionId}', 'admin::permissions.update')->name('permissions.update')->can('admin.permissions.update');
    });
});

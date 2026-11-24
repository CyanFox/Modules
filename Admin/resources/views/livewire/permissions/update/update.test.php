<?php

use Livewire\Livewire;
use Modules\Core\Models\Permission;

beforeEach(fn () => seedCoreSettings());

it('renders with the permission data', function () {
    $permission = Permission::findOrCreate('test.permission', 'web');

    $this->actingAs(makeUser([], allAdminPermissions()));

    Livewire::test('admin::permissions.update', ['permissionId' => $permission->id])
        ->assertStatus(200)
        ->assertSet('name', 'test.permission')
        ->assertSet('guardName', 'web');
});

it('aborts when the permission does not exist', function () {
    $this->actingAs(makeUser([], allAdminPermissions()));

    Livewire::test('admin::permissions.update', ['permissionId' => 999999])
        ->assertStatus(404);
});

it('updates the permission', function () {
    $permission = Permission::findOrCreate('test.permission', 'web');

    $this->actingAs(makeUser([], allAdminPermissions()));

    Livewire::test('admin::permissions.update', ['permissionId' => $permission->id])
        ->set('name', 'test.permission.renamed')
        ->call('updatePermission')
        ->assertRedirect(route('admin.permissions'));

    $permission->refresh();

    expect($permission->name)->toBe('test.permission.renamed');
});

it('shows validation errors for a missing name', function () {
    $permission = Permission::findOrCreate('test.permission', 'web');

    $this->actingAs(makeUser([], allAdminPermissions()));

    Livewire::test('admin::permissions.update', ['permissionId' => $permission->id])
        ->set('name', '')
        ->call('updatePermission')
        ->assertHasErrors('name');

    $permission->refresh();

    expect($permission->name)->toBe('test.permission');
});

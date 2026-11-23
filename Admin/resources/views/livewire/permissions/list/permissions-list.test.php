<?php

use Livewire\Livewire;
use Modules\Core\Models\Permission;

beforeEach(fn () => seedCoreSettings());

it('renders successfully', function () {
    $this->actingAs(makeUser([], allAdminPermissions()));

    Livewire::test('admin::permissions.list')
        ->assertStatus(200);
});

it('shows the permissions', function () {
    Permission::findOrCreate('admin.users', 'web');

    $this->actingAs(makeUser([], allAdminPermissions()));

    Livewire::test('admin::permissions.list')
        ->assertSee('admin.users');
});

it('filters the permissions by search', function () {
    Permission::findOrCreate('admin.users', 'web');
    Permission::findOrCreate('admin.roles', 'web');

    $this->actingAs(makeUser([], allAdminPermissions()));

    Livewire::test('admin::permissions.list')
        ->set('search', 'admin.users')
        ->assertSee('admin.users')
        ->assertDontSee('admin.roles');
});

it('deletes a permission when confirmed', function () {
    $permission = Permission::findOrCreate('test.permission', 'web');

    $this->actingAs(makeUser([], allAdminPermissions()));

    Livewire::test('admin::permissions.list')
        ->call('deletePermission', $permission->id, true)
        ->assertRedirect();

    expect(Permission::find($permission->id))->toBeNull();
});

it('does not delete a permission without the permission', function () {
    $permission = Permission::findOrCreate('test.permission', 'web');

    $this->actingAs(makeUser());

    Livewire::test('admin::permissions.list')
        ->call('deletePermission', $permission->id, true);

    expect(Permission::find($permission->id))->not->toBeNull();
});

<?php

use Livewire\Livewire;
use Modules\Core\Models\Permission;
use Modules\Core\Models\Role;

beforeEach(fn () => seedCoreSettings());

it('renders with the role data', function () {
    $role = Role::create(['name' => 'Manager', 'guard_name' => 'web']);

    $this->actingAs(makeUser([], allAdminPermissions()));

    Livewire::test('admin::roles.update', ['roleId' => $role->id])
        ->assertStatus(200)
        ->assertSet('name', 'Manager')
        ->assertSet('guardName', 'web');
});

it('aborts when the role does not exist', function () {
    $this->actingAs(makeUser([], allAdminPermissions()));

    Livewire::test('admin::roles.update', ['roleId' => 999999])
        ->assertStatus(404);
});

it('updates the role', function () {
    $role = Role::create(['name' => 'Manager', 'guard_name' => 'web']);

    $this->actingAs(makeUser([], allAdminPermissions()));

    Livewire::test('admin::roles.update', ['roleId' => $role->id])
        ->set('name', 'Supervisor')
        ->call('updateRole')
        ->assertRedirect(route('admin.roles'));

    $role->refresh();

    expect($role->name)->toBe('Supervisor');
});

it('syncs the permissions', function () {
    Permission::findOrCreate('admin.roles', 'web');
    $role = Role::create(['name' => 'Manager', 'guard_name' => 'web']);

    $this->actingAs(makeUser([], allAdminPermissions()));

    Livewire::test('admin::roles.update', ['roleId' => $role->id])
        ->set('permissions', ['admin.roles'])
        ->call('updateRole')
        ->assertRedirect();

    $role->refresh();

    expect($role->permissions->pluck('name')->all())->toContain('admin.roles');
});

it('shows validation errors for a missing name', function () {
    $role = Role::create(['name' => 'Manager', 'guard_name' => 'web']);

    $this->actingAs(makeUser([], allAdminPermissions()));

    Livewire::test('admin::roles.update', ['roleId' => $role->id])
        ->set('name', '')
        ->call('updateRole')
        ->assertHasErrors('name');

    $role->refresh();

    expect($role->name)->toBe('Manager');
});

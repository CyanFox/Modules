<?php

use Livewire\Livewire;
use Modules\Core\Models\Permission;
use Modules\Core\Models\Role;

beforeEach(fn () => seedCoreSettings());

it('renders successfully', function () {
    $this->actingAs(makeUser([], allAdminPermissions()));

    Livewire::test('admin::roles.create')
        ->assertStatus(200);
});

it('creates a role', function () {
    Permission::findOrCreate('admin.users', 'web');

    $this->actingAs(makeUser([], allAdminPermissions()));

    Livewire::test('admin::roles.create')
        ->set('name', 'Manager')
        ->set('guardName', 'web')
        ->set('permissions', ['admin.users'])
        ->call('createRole')
        ->assertRedirect(route('admin.roles'));

    $role = Role::where('name', 'Manager')->first();

    expect($role)->not->toBeNull();
    expect($role->permissions->pluck('name')->all())->toContain('admin.users');
});

it('shows validation errors for a missing name', function () {
    $this->actingAs(makeUser([], allAdminPermissions()));

    Livewire::test('admin::roles.create')
        ->call('createRole')
        ->assertHasErrors('name');

    expect(Role::count())->toBe(0);
});

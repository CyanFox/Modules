<?php

use Livewire\Livewire;
use Modules\Core\Models\Role;

beforeEach(fn () => seedCoreSettings());

it('renders successfully', function () {
    $this->actingAs(makeUser([], allAdminPermissions()));

    Livewire::test('admin::roles.list')
        ->assertStatus(200);
});

it('shows the roles', function () {
    Role::create(['name' => 'Manager', 'guard_name' => 'web']);

    $this->actingAs(makeUser([], allAdminPermissions()));

    Livewire::test('admin::roles.list')
        ->assertSee('Manager');
});

it('filters the roles by search', function () {
    Role::create(['name' => 'Manager', 'guard_name' => 'web']);
    Role::create(['name' => 'Support', 'guard_name' => 'web']);

    $this->actingAs(makeUser([], allAdminPermissions()));

    Livewire::test('admin::roles.list')
        ->set('search', 'Manager')
        ->assertSee('Manager')
        ->assertDontSee('Support');
});

it('deletes a role when confirmed', function () {
    $role = Role::create(['name' => 'Manager', 'guard_name' => 'web']);

    $this->actingAs(makeUser([], allAdminPermissions()));

    Livewire::test('admin::roles.list')
        ->call('deleteRole', $role->id, true)
        ->assertRedirect();

    expect(Role::find($role->id))->toBeNull();
});

it('does not delete a role without the permission', function () {
    $role = Role::create(['name' => 'Manager', 'guard_name' => 'web']);

    $this->actingAs(makeUser());

    Livewire::test('admin::roles.list')
        ->call('deleteRole', $role->id, true);

    expect(Role::find($role->id))->not->toBeNull();
});

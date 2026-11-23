<?php

use Livewire\Livewire;
use Modules\Core\Models\Permission;

beforeEach(fn () => seedCoreSettings());

it('renders successfully', function () {
    $this->actingAs(makeUser([], allAdminPermissions()));

    Livewire::test('admin::permissions.create')
        ->assertStatus(200);
});

it('creates a permission', function () {
    $this->actingAs(makeUser([], allAdminPermissions()));

    Livewire::test('admin::permissions.create')
        ->set('name', 'test.permission')
        ->set('guardName', 'web')
        ->call('createPermission')
        ->assertRedirect(route('admin.permissions'));

    expect(Permission::where('name', 'test.permission')->exists())->toBeTrue();
});

it('shows validation errors for a missing name', function () {
    $this->actingAs(makeUser([], allAdminPermissions()));

    Livewire::test('admin::permissions.create')
        ->call('createPermission')
        ->assertHasErrors('name');
});

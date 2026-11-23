<?php

use Livewire\Livewire;

beforeEach(fn () => seedCoreSettings());

it('renders successfully', function () {
    $this->actingAs(makeUser([], allAdminPermissions()));

    Livewire::test('admin::activity')
        ->assertStatus(200);
});

it('shows the activities', function () {
    $admin = makeUser([], allAdminPermissions());
    $target = makeUser();

    $this->actingAs($admin);

    activity()->performedOn($target)->causedBy($admin)->log('user.updated');

    Livewire::test('admin::activity')
        ->set('perPage', 50)
        ->assertSee('user.updated')
        ->assertSee($admin->getDisplayName());
});

it('filters the activities by search', function () {
    $admin = makeUser([], allAdminPermissions());

    $this->actingAs($admin);

    activity()->log('user.created');
    activity()->log('role.deleted');

    Livewire::test('admin::activity')
        ->set('perPage', 50)
        ->set('search', 'role.deleted')
        ->assertSee('role.deleted')
        ->assertDontSee('user.created');
});

<?php

use Livewire\Livewire;
use Modules\Core\Models\User;

beforeEach(fn () => seedCoreSettings());

it('renders successfully', function () {
    $this->actingAs(makeUser([], allAdminPermissions()));

    Livewire::test('admin::users.list')
        ->assertStatus(200);
});

it('shows the users', function () {
    $this->actingAs(makeUser([], allAdminPermissions()));

    makeUser(['username' => 'visible-user', 'email' => 'visible@example.com']);

    Livewire::test('admin::users.list')
        ->assertSee('visible-user')
        ->assertSee('visible@example.com');
});

it('filters the users by search', function () {
    $this->actingAs(makeUser([], allAdminPermissions()));

    makeUser(['username' => 'alpha-user']);
    makeUser(['username' => 'beta-user']);

    Livewire::test('admin::users.list')
        ->set('search', 'alpha')
        ->assertSee('alpha-user')
        ->assertDontSee('beta-user');
});

it('sorts the users by a column', function () {
    $this->actingAs(makeUser([], allAdminPermissions()));

    $component = Livewire::test('admin::users.list')
        ->call('sort', 'username')
        ->assertSet('sortField', 'username')
        ->assertSet('sortDirection', 'asc');

    $component->call('sort', 'username')
        ->assertSet('sortDirection', 'desc');

    $component->call('sort', 'username')
        ->assertSet('sortField', '');
});

it('deletes a user when confirmed', function () {
    $admin = makeUser([], allAdminPermissions());
    $victim = makeUser();

    $this->actingAs($admin);

    Livewire::test('admin::users.list')
        ->call('deleteUser', $victim->id, true)
        ->assertRedirect();

    expect(User::find($victim->id))->toBeNull();
});

it('does not delete a user without the permission', function () {
    $this->actingAs(makeUser());

    $victim = makeUser();

    Livewire::test('admin::users.list')
        ->call('deleteUser', $victim->id, true);

    expect(User::find($victim->id))->not->toBeNull();
});

it('does not delete itself', function () {
    $admin = makeUser([], allAdminPermissions());

    $this->actingAs($admin);

    Livewire::test('admin::users.list')
        ->call('deleteUser', $admin->id, true);

    expect(User::find($admin->id))->not->toBeNull();
});

it('impersonates a user when allowed', function () {
    $admin = makeUser([], [...allAdminPermissions(), 'core.impersonate']);
    $victim = makeUser();

    $this->actingAs($admin);

    Livewire::test('admin::users.list')
        ->call('impersonate', $victim->id)
        ->assertRedirect();

    $admin->refresh();

    expect($admin->isImpersonated())->toBeTrue();
});

it('does not impersonate immune users', function () {
    $admin = makeUser([], [...allAdminPermissions(), 'core.impersonate']);
    $immune = makeUser([], ['core.impersonate.immune']);

    $this->actingAs($admin);

    Livewire::test('admin::users.list')
        ->call('impersonate', $immune->id);

    $admin->refresh();

    expect($admin->isImpersonated())->toBeFalse();
});

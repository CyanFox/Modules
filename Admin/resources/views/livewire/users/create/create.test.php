<?php

use Livewire\Livewire;
use Modules\Core\Models\Role;
use Modules\Core\Models\User;

beforeEach(fn () => seedCoreSettings());

it('renders successfully', function () {
    $this->actingAs(makeUser([], allAdminPermissions()));

    Livewire::test('admin::users.create')
        ->assertStatus(200);
});

it('creates a user', function () {
    $role = Role::create(['name' => 'Manager', 'guard_name' => 'web']);

    $this->actingAs(makeUser([], allAdminPermissions()));

    Livewire::test('admin::users.create')
        ->set('firstName', 'Jane')
        ->set('lastName', 'Doe')
        ->set('username', 'jane.doe')
        ->set('email', 'jane@example.com')
        ->set('password', 'Password123!')
        ->set('passwordConfirmation', 'Password123!')
        ->set('roles', [$role->name])
        ->set('forcePasswordChange', true)
        ->set('forceMfa', true)
        ->call('createUser')
        ->assertRedirect(route('admin.users'));

    $user = User::where('username', 'jane.doe')->first();

    expect($user)->not->toBeNull();
    expect($user->first_name)->toBe('Jane');
    expect($user->last_name)->toBe('Doe');
    expect($user->roles->pluck('name')->all())->toContain('Manager');
    expect(userSettings('auth.force_password_change', $user->id))->toBeTrue();
    expect(userSettings('auth.force_mfa', $user->id))->toBeTrue();
});

it('shows validation errors for missing fields', function () {
    $this->actingAs(makeUser([], allAdminPermissions()));

    Livewire::test('admin::users.create')
        ->set('password', 'Password123!')
        ->set('passwordConfirmation', 'Password123!')
        ->call('createUser')
        ->assertHasErrors(['username', 'email']);

    expect(User::count())->toBe(1); // only the acting admin
});

it('shows validation errors for an existing username', function () {
    makeUser(['username' => 'taken-user']);

    $this->actingAs(makeUser([], allAdminPermissions()));

    Livewire::test('admin::users.create')
        ->set('username', 'taken-user')
        ->set('email', 'taken@example.com')
        ->set('password', 'Password123!')
        ->set('passwordConfirmation', 'Password123!')
        ->call('createUser')
        ->assertHasErrors('username');
});

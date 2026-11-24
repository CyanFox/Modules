<?php

use Illuminate\Support\Facades\Hash;
use Livewire\Livewire;

beforeEach(fn () => seedCoreSettings());

it('renders with the user data', function () {
    $user = makeUser(['username' => 'editable', 'first_name' => 'Edit', 'last_name' => 'Me', 'email' => 'edit@example.com']);

    $this->actingAs(makeUser([], allAdminPermissions()));

    Livewire::test('admin::users.update', ['userId' => $user->id])
        ->assertStatus(200)
        ->assertSet('username', 'editable')
        ->assertSet('firstName', 'Edit')
        ->assertSet('lastName', 'Me')
        ->assertSet('email', 'edit@example.com');
});

it('aborts when the user does not exist', function () {
    $this->actingAs(makeUser([], allAdminPermissions()));

    Livewire::test('admin::users.update', ['userId' => 999999])
        ->assertStatus(404);
});

it('updates the user', function () {
    $user = makeUser();

    $this->actingAs(makeUser([], allAdminPermissions()));

    Livewire::test('admin::users.update', ['userId' => $user->id])
        ->set('firstName', 'Updated')
        ->set('username', 'updated-user')
        ->set('email', 'updated@example.com')
        ->call('updateUser')
        ->assertRedirect(route('admin.users'));

    $user->refresh();

    expect($user->first_name)->toBe('Updated');
    expect($user->username)->toBe('updated-user');
    expect($user->email)->toBe('updated@example.com');
});

it('changes the password when one is provided', function () {
    $user = makeUser();

    $this->actingAs(makeUser([], allAdminPermissions()));

    Livewire::test('admin::users.update', ['userId' => $user->id])
        ->set('password', 'NewPassword456!')
        ->set('passwordConfirmation', 'NewPassword456!')
        ->call('updateUser')
        ->assertRedirect();

    $user->refresh();

    expect(Hash::check('NewPassword456!', $user->password))->toBeTrue();
});

it('shows validation errors for an existing username', function () {
    makeUser(['username' => 'other-user']);
    $user = makeUser();

    $this->actingAs(makeUser([], allAdminPermissions()));

    Livewire::test('admin::users.update', ['userId' => $user->id])
        ->set('username', 'other-user')
        ->call('updateUser')
        ->assertHasErrors('username');

    $user->refresh();

    expect($user->username)->not->toBe('other-user');
});

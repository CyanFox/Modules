<?php

use Livewire\Livewire;
use Modules\Core\Models\User;

it('renders successfully', function () {
    Livewire::test('auth::register')
        ->assertStatus(200);
});

it('registers a new user', function () {
    setSetting('auth.login.rate_limit', 10);

    Livewire::test('auth::register')
        ->set('username', 'newuser')
        ->set('email', 'newuser@example.com')
        ->set('password', 'Password123!')
        ->set('passwordConfirmation', 'Password123!')
        ->call('register')
        ->assertRedirect(route('auth.login'));

    expect(User::where('username', 'newuser')->exists())->toBeTrue();
});

it('shows validation errors when the username is taken', function () {
    setSetting('auth.login.rate_limit', 10);

    makeUser(['username' => 'newuser']);

    Livewire::test('auth::register')
        ->set('username', 'newuser')
        ->set('email', 'newuser@example.com')
        ->set('password', 'Password123!')
        ->set('passwordConfirmation', 'Password123!')
        ->call('register')
        ->assertHasErrors('username');
});

it('shows validation errors when the passwords do not match', function () {
    setSetting('auth.login.rate_limit', 10);

    Livewire::test('auth::register')
        ->set('username', 'newuser')
        ->set('email', 'newuser@example.com')
        ->set('password', 'Password123!')
        ->set('passwordConfirmation', 'Different123!')
        ->call('register')
        ->assertHasErrors('passwordConfirmation');

    expect(User::where('username', 'newuser')->exists())->toBeFalse();
});

<?php

use Illuminate\Support\Facades\Hash;
use Livewire\Livewire;
use Modules\Core\Facades\UserSettings;
use Modules\Core\Models\User;

beforeEach(fn () => seedCoreSettings());

it('renders successfully', function () {
    $this->actingAs(makeUser());

    Livewire::test('account::profile')
        ->assertStatus(200);
});

it('updates the profile', function () {
    $user = makeUser();
    $this->actingAs($user);

    Livewire::test('account::profile')
        ->set('firstName', 'Updated')
        ->set('lastName', 'Name')
        ->set('username', 'updated-username')
        ->set('email', 'updated@example.com')
        ->call('updateProfile')
        ->assertRedirect();

    $user->refresh();

    expect($user->first_name)->toBe('Updated');
    expect($user->last_name)->toBe('Name');
    expect($user->username)->toBe('updated-username');
    expect($user->email)->toBe('updated@example.com');
});

it('shows validation errors when the username is taken', function () {
    makeUser(['username' => 'taken-username']);
    $user = makeUser();
    $this->actingAs($user);

    Livewire::test('account::profile')
        ->set('username', 'taken-username')
        ->call('updateProfile')
        ->assertHasErrors('username');

    $user->refresh();

    expect($user->username)->not->toBe('taken-username');
});

it('changes the password', function () {
    $user = makeUser();
    $this->actingAs($user);

    Livewire::test('account::profile')
        ->set('currentPassword', 'Password123!')
        ->set('newPassword', 'NewPassword456!')
        ->set('confirmNewPassword', 'NewPassword456!')
        ->call('changePassword')
        ->assertRedirect();

    $user->refresh();

    expect(Hash::check('NewPassword456!', $user->password))->toBeTrue();
});

it('fails to change the password with a wrong current password', function () {
    $user = makeUser();
    $this->actingAs($user);

    Livewire::test('account::profile')
        ->set('currentPassword', 'WrongPassword1!')
        ->set('newPassword', 'NewPassword456!')
        ->set('confirmNewPassword', 'NewPassword456!')
        ->call('changePassword')
        ->assertHasErrors('currentPassword');

    $user->refresh();

    expect(Hash::check('Password123!', $user->password))->toBeTrue();
});

it('updates the theme', function () {
    $user = makeUser();
    $this->actingAs($user);

    Livewire::test('account::profile')
        ->set('theme', 'dark')
        ->call('updateTheme')
        ->assertRedirect();

    $user->refresh();

    expect($user->theme)->toBe('dark');
});

it('updates the language', function () {
    $user = makeUser();
    $this->actingAs($user);

    Livewire::test('account::profile')
        ->set('language', 'de')
        ->call('updateLanguage')
        ->assertRedirect();

    $user->refresh();

    expect($user->language)->toBe('de');
});

it('does not delete the account when the feature is disabled', function () {
    $user = makeUser();
    $this->actingAs($user);

    session(['core.password_confirmed_at' => time()]);

    Livewire::test('account::profile')
        ->call('deleteAccount', true);

    expect(User::find($user->id))->not->toBeNull();
});

it('deletes the account when enabled and the password is confirmed', function () {
    setSetting('account.enable.delete_account', true);

    $user = makeUser();
    $this->actingAs($user);

    session(['core.password_confirmed_at' => time()]);

    Livewire::test('account::profile')
        ->call('deleteAccount', true)
        ->assertRedirect(route('auth.login'));

    expect(User::find($user->id))->toBeNull();
});

it('disables mfa when confirmed', function () {
    $user = makeUser();
    UserSettings::set($user->id, 'auth.mfa.enabled', true);
    UserSettings::set($user->id, 'auth.mfa.secret', 'JBSWY3DPEHPK3PXP');
    $this->actingAs($user);

    session(['core.password_confirmed_at' => time()]);

    Livewire::test('account::profile')
        ->call('disableMfa', true)
        ->assertRedirect();

    expect(userSettings('auth.mfa.enabled', $user->id))->toBeNull();
    expect(userSettings('auth.mfa.secret', $user->id))->toBeNull();
});

it('asks for password confirmation when disabling mfa', function () {
    $user = makeUser();
    UserSettings::set($user->id, 'auth.mfa.enabled', true);
    $this->actingAs($user);

    Livewire::test('account::profile')
        ->call('disableMfa')
        ->assertDispatched('openModal', 'core::components.modals.confirmation');

    expect(userSettings('auth.mfa.enabled', $user->id))->toBeTrue();
});

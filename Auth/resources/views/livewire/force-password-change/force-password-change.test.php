<?php

use Illuminate\Support\Facades\Hash;
use Livewire\Livewire;
use Modules\Core\Facades\UserSettings;

beforeEach(fn () => seedCoreSettings());

it('renders successfully', function () {
    $this->actingAs(makeUser());

    Livewire::test('auth::force-password-change')
        ->assertStatus(200);
});

it('changes the password and removes the forced flag', function () {
    $user = makeUser();
    UserSettings::set($user->id, 'auth.force_password_change', true);
    $this->actingAs($user);

    Livewire::test('auth::force-password-change')
        ->set('currentPassword', 'Password123!')
        ->set('newPassword', 'NewPassword456!')
        ->set('confirmNewPassword', 'NewPassword456!')
        ->call('changePassword')
        ->assertRedirect();

    $user->refresh();

    expect(Hash::check('NewPassword456!', $user->password))->toBeTrue();
    expect(userSettings('auth.force_password_change', $user->id))->toBeNull();
});

it('fails when the current password is wrong', function () {
    $user = makeUser();
    $this->actingAs($user);

    Livewire::test('auth::force-password-change')
        ->set('currentPassword', 'WrongPassword1!')
        ->set('newPassword', 'NewPassword456!')
        ->set('confirmNewPassword', 'NewPassword456!')
        ->call('changePassword')
        ->assertHasErrors('currentPassword');

    $user->refresh();

    expect(Hash::check('Password123!', $user->password))->toBeTrue();
});

it('fails when the new password equals the current one', function () {
    $user = makeUser();
    $this->actingAs($user);

    Livewire::test('auth::force-password-change')
        ->set('currentPassword', 'Password123!')
        ->set('newPassword', 'Password123!')
        ->set('confirmNewPassword', 'Password123!')
        ->call('changePassword')
        ->assertHasErrors('newPassword');
});

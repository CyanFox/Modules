<?php

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Livewire\Livewire;
use Modules\Auth\Emails\ForgotPasswordMail;
use Modules\Core\Enums\SettingsProperty;
use Modules\Core\Facades\UserSettings;

beforeEach(fn () => seedCoreSettings());

it('renders successfully', function () {
    setSetting('auth.forgot_password.enabled', true);

    Livewire::test('auth::forgot-password')
        ->assertStatus(200);
});

it('aborts when the forgot password feature is disabled', function () {
    Livewire::test('auth::forgot-password')
        ->assertStatus(404);
});

it('sends a reset link and stores a token', function () {
    setSetting('auth.forgot_password.enabled', true);
    setSetting('auth.forgot_password.rate_limit', 10);

    Mail::fake();

    $user = makeUser();

    Livewire::test('auth::forgot-password')
        ->set('username', $user->username)
        ->call('sendResetLink')
        ->assertRedirect();

    Mail::assertQueued(ForgotPasswordMail::class);

    expect(userSettings('auth.forgot_password.token', $user->id))->not->toBeNull();
    expect(userSettings('auth.forgot_password.expiration', $user->id))->not->toBeNull();
});

it('does not send a reset link for unknown users', function () {
    setSetting('auth.forgot_password.enabled', true);
    setSetting('auth.forgot_password.rate_limit', 10);

    Mail::fake();

    Livewire::test('auth::forgot-password')
        ->set('username', 'no-such-user')
        ->call('sendResetLink')
        ->assertRedirect();

    Mail::assertNothingQueued();
});

it('resets the password with a valid token', function () {
    setSetting('auth.forgot_password.enabled', true);
    setSetting('auth.forgot_password.rate_limit', 10);

    $user = makeUser();
    $token = 'test-token-1234567890';

    UserSettings::set($user->id, 'auth.forgot_password.token', $token, true, [SettingsProperty::INTERNAL => true, SettingsProperty::HIDDEN => true]);
    UserSettings::set($user->id, 'auth.forgot_password.expiration', now()->addHour(), true, [SettingsProperty::INTERNAL => true, SettingsProperty::HIDDEN => true]);

    Livewire::test('auth::forgot-password', ['token' => $token])
        ->set('password', 'NewPassword456!')
        ->set('passwordConfirmation', 'NewPassword456!')
        ->call('resetPassword')
        ->assertRedirect(route('auth.login'));

    $user->refresh();

    expect(Hash::check('NewPassword456!', $user->password))->toBeTrue();
    expect(userSettings('auth.forgot_password.token', $user->id))->toBeNull();
});

it('redirects when the token is invalid', function () {
    setSetting('auth.forgot_password.enabled', true);

    Livewire::test('auth::forgot-password', ['token' => 'invalid-token'])
        ->assertRedirect(route('auth.forgot-password'));
});

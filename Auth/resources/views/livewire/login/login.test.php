<?php

use Livewire\Livewire;
use Modules\Core\Enums\SettingsProperty;
use Modules\Core\Facades\UserSettings;
use PragmaRX\Google2FA\Google2FA;

it('renders successfully', function () {
    Livewire::test('auth::login')
        ->assertStatus(200);
});

it('logs in with valid credentials', function () {
    setSetting('auth.login.rate_limit', 10);

    $user = makeUser();

    Livewire::test('auth::login')
        ->set('username', $user->username)
        ->set('password', 'Password123!')
        ->call('attemptLogin')
        ->assertRedirect();

    expect(auth()->id())->toBe($user->id);
});

it('fails to log in with a wrong password', function () {
    setSetting('auth.login.rate_limit', 10);

    $user = makeUser();

    Livewire::test('auth::login')
        ->set('username', $user->username)
        ->set('password', 'WrongPassword1!')
        ->call('attemptLogin')
        ->assertHasErrors('username');

    expect(auth()->check())->toBeFalse();
});

it('fails to log in when the user does not exist', function () {
    setSetting('auth.login.rate_limit', 10);

    Livewire::test('auth::login')
        ->set('username', 'no-such-user')
        ->set('password', 'Password123!')
        ->call('attemptLogin')
        ->assertHasErrors('username');

    expect(auth()->check())->toBeFalse();
});

it('fails to log in when the user is disabled', function () {
    setSetting('auth.login.rate_limit', 10);

    $user = makeUser(['disabled' => true]);

    Livewire::test('auth::login')
        ->set('username', $user->username)
        ->set('password', 'Password123!')
        ->call('attemptLogin')
        ->assertHasErrors('username');

    expect(auth()->check())->toBeFalse();
});

it('requires an mfa code when mfa is enabled', function () {
    seedCoreSettings();
    setSetting('auth.login.enabled', true);
    setSetting('auth.login.rate_limit', 10);

    $user = makeUser();
    UserSettings::set($user->id, 'auth.mfa.enabled', true);
    UserSettings::set($user->id, 'auth.mfa.secret', 'JBSWY3DPEHPK3PXP');

    Livewire::test('auth::login')
        ->set('username', $user->username)
        ->set('password', 'Password123!')
        ->call('attemptLogin')
        ->assertSeeHtml('mfaCode');

    expect(auth()->check())->toBeFalse();
});

it('logs in with a valid mfa code', function () {
    seedCoreSettings();
    setSetting('auth.login.enabled', true);
    setSetting('auth.login.rate_limit', 10);

    $user = makeUser();
    $secret = 'JBSWY3DPEHPK3PXP';
    UserSettings::set($user->id, 'auth.mfa.enabled', true);
    UserSettings::set($user->id, 'auth.mfa.secret', $secret);

    $code = (new Google2FA)->getCurrentOtp($secret);

    Livewire::test('auth::login')
        ->set('username', $user->username)
        ->set('password', 'Password123!')
        ->call('attemptLogin')
        ->set('mfaCode', $code)
        ->call('checkMfaCode')
        ->assertRedirect();

    expect(auth()->id())->toBe($user->id);
});

it('rejects an invalid mfa code', function () {
    seedCoreSettings();
    setSetting('auth.login.enabled', true);
    setSetting('auth.login.rate_limit', 10);

    $user = makeUser();
    $secret = 'JBSWY3DPEHPK3PXP';
    UserSettings::set($user->id, 'auth.mfa.enabled', true);
    UserSettings::set($user->id, 'auth.mfa.secret', $secret);
    UserSettings::set($user->id, 'auth.mfa.recovery_codes', ['used-recovery-code'], true, [SettingsProperty::INTERNAL => true, SettingsProperty::ENCRYPTED => true]);

    Livewire::test('auth::login')
        ->set('username', $user->username)
        ->set('password', 'Password123!')
        ->call('attemptLogin')
        ->set('mfaCode', '000000')
        ->call('checkMfaCode')
        ->assertHasErrors('mfaCode');

    expect(auth()->check())->toBeFalse();
});

<?php

use Livewire\Livewire;
use Modules\Core\Facades\UserSettings;
use PragmaRX\Google2FA\Google2FA;

beforeEach(fn () => seedCoreSettings());

it('renders successfully', function () {
    $this->actingAs(makeUser());

    Livewire::test('auth::force-mfa')
        ->assertStatus(200);
});

it('generates a mfa secret on mount', function () {
    $user = makeUser();
    $this->actingAs($user);

    Livewire::test('auth::force-mfa');

    expect(userSettings('auth.mfa.secret', $user->id))->not->toBeNull();
});

it('enables mfa with a valid code', function () {
    $user = makeUser();
    $this->actingAs($user);

    $component = Livewire::test('auth::force-mfa');
    $secret = userSettings('auth.mfa.secret', $user->id);

    $component
        ->set('code', (new Google2FA)->getCurrentOtp($secret))
        ->call('enableMfa');

    expect(userSettings('auth.mfa.enabled', $user->id))->toBeTrue();
    expect(userSettings('auth.mfa.recovery_codes', $user->id))->toHaveCount(8);
});

it('rejects an invalid code', function () {
    $user = makeUser();
    $this->actingAs($user);

    Livewire::test('auth::force-mfa')
        ->set('code', '000000')
        ->call('enableMfa')
        ->assertHasErrors('code');

    expect(userSettings('auth.mfa.enabled', $user->id))->toBeNull();
});

it('removes the forced mfa flag on finish', function () {
    $user = makeUser();
    UserSettings::set($user->id, 'auth.force_mfa', true);
    $this->actingAs($user);

    Livewire::test('auth::force-mfa')
        ->call('finish')
        ->assertRedirect();

    expect(userSettings('auth.force_mfa', $user->id))->toBeNull();
});

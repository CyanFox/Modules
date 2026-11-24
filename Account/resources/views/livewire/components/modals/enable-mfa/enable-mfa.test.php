<?php

use Livewire\Livewire;
use PragmaRX\Google2FA\Google2FA;

it('renders successfully', function () {
    $this->actingAs(makeUser());
    $this->withSession(['core.password_confirmed_at' => time()]);
    $token = passwordConfirmationToken();

    Livewire::test('account::components.modals.enable-mfa', ['passwordConfirmationToken' => $token])
        ->assertStatus(200);
});

it('generates a mfa secret on mount', function () {
    $user = makeUser();
    $this->actingAs($user);
    $this->withSession(['core.password_confirmed_at' => time()]);
    $token = passwordConfirmationToken();

    Livewire::test('account::components.modals.enable-mfa', ['passwordConfirmationToken' => $token]);

    expect(userSettings('auth.mfa.secret', $user->id))->not->toBeNull();
});

it('enables mfa with a valid code', function () {
    $user = makeUser();
    $this->actingAs($user);
    $this->withSession(['core.password_confirmed_at' => time()]);
    $token = passwordConfirmationToken();

    $component = Livewire::test('account::components.modals.enable-mfa', ['passwordConfirmationToken' => $token]);
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
    $this->withSession(['core.password_confirmed_at' => time()]);
    $token = passwordConfirmationToken();

    Livewire::test('account::components.modals.enable-mfa', ['passwordConfirmationToken' => $token])
        ->set('code', '000000')
        ->call('enableMfa')
        ->assertHasErrors('code');

    expect(userSettings('auth.mfa.enabled', $user->id))->toBeNull();
});

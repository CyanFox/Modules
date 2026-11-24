<?php

use Livewire\Livewire;

it('renders successfully', function () {
    $this->actingAs(makeUser());
    $this->withSession(['core.password_confirmed_at' => time()]);
    $token = passwordConfirmationToken();

    Livewire::test('account::components.modals.regenerate-recovery-codes', ['passwordConfirmationToken' => $token])
        ->assertStatus(200);
});

it('generates recovery codes on mount', function () {
    $user = makeUser();
    $this->actingAs($user);
    $this->withSession(['core.password_confirmed_at' => time()]);
    $token = passwordConfirmationToken();

    Livewire::test('account::components.modals.regenerate-recovery-codes', ['passwordConfirmationToken' => $token]);

    expect(userSettings('auth.mfa.recovery_codes', $user->id))->toHaveCount(8);
});

it('regenerates the recovery codes', function () {
    $user = makeUser();
    $this->actingAs($user);
    $this->withSession(['core.password_confirmed_at' => time()]);
    $token = passwordConfirmationToken();

    $component = Livewire::test('account::components.modals.regenerate-recovery-codes', ['passwordConfirmationToken' => $token]);

    $oldCodes = userSettings('auth.mfa.recovery_codes', $user->id);

    $component->call('regenerateCodes');

    $newCodes = userSettings('auth.mfa.recovery_codes', $user->id);

    expect($newCodes)->toHaveCount(8);
    expect($newCodes)->not->toBe($oldCodes);
});

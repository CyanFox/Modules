<?php

use Livewire\Livewire;

it('renders successfully', function () {
    $this->actingAs(makeUser());

    Livewire::test('account::components.modals.regenerate-recovery-codes')
        ->assertStatus(200);
});

it('generates recovery codes on mount', function () {
    $user = makeUser();
    $this->actingAs($user);

    Livewire::test('account::components.modals.regenerate-recovery-codes');

    expect(userSettings('auth.mfa.recovery_codes', $user->id))->toHaveCount(8);
});

it('regenerates the recovery codes', function () {
    $user = makeUser();
    $this->actingAs($user);

    $component = Livewire::test('account::components.modals.regenerate-recovery-codes');

    $oldCodes = userSettings('auth.mfa.recovery_codes', $user->id);

    $component->call('regenerateCodes');

    $newCodes = userSettings('auth.mfa.recovery_codes', $user->id);

    expect($newCodes)->toHaveCount(8);
    expect($newCodes)->not->toBe($oldCodes);
});

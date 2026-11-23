<?php

use Livewire\Livewire;

it('renders successfully', function () {
    $this->actingAs(makeUser());

    Livewire::test('account::components.passkeys')
        ->assertStatus(200);
});

it('validates the passkey properties and generates options', function () {
    $this->actingAs(makeUser());

    Livewire::test('account::components.passkeys')
        ->set('name', 'My Passkey')
        ->call('validatePasskeyProperties')
        ->assertDispatched('passkeyPropertiesValidated');

    expect(session('passkey-registration-options'))->not->toBeNull();
});

it('shows a validation error without a name', function () {
    $this->actingAs(makeUser());

    Livewire::test('account::components.passkeys')
        ->set('name', '')
        ->call('validatePasskeyProperties')
        ->assertHasErrors('name');
});

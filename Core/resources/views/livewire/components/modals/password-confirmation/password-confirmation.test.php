<?php

use Livewire\Livewire;

it('renders successfully', function () {
    $this->actingAs(makeUser());

    Livewire::test('core::components.modals.password-confirmation')
        ->assertStatus(200);
});

it('rejects a wrong password', function () {
    $this->actingAs(makeUser());

    Livewire::test('core::components.modals.password-confirmation', [
        'event' => 'doSomething(5)',
    ])
        ->set('password', 'WrongPassword1!')
        ->call('confirmPassword')
        ->assertHasErrors('password')
        ->assertNotDispatched('core.passwordConfirmed');
});

it('confirms with the correct password', function () {
    $this->actingAs(makeUser());

    Livewire::test('core::components.modals.password-confirmation', [
        'event' => 'doSomething(5)',
    ])
        ->set('password', 'Password123!')
        ->call('confirmPassword')
        ->assertDispatched('core.passwordConfirmed', 'doSomething(5)')
        ->assertDispatched('closeModal');

    expect(session('core.password_confirmed_at'))->not->toBeNull();
});

it('cancels without confirming', function () {
    $this->actingAs(makeUser());

    Livewire::test('core::components.modals.password-confirmation', [
        'event' => 'doSomething(5)',
        'cancelEvent' => 'cancelSomething',
    ])
        ->call('cancelPasswordConfirmation')
        ->assertDispatched('core.passwordConfirmed', 'cancelSomething')
        ->assertDispatched('closeModal');

    expect(session('core.password_confirmed_at'))->toBeNull();
});

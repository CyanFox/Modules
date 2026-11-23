<?php

use Livewire\Livewire;

it('renders successfully with a valid password confirmation token', function () {
    $this->actingAs(makeUser());

    $token = str()->random(64);
    cache()->put('core.password_confirmation.'.hash('sha256', $token), true, now()->addMinutes(5));

    Livewire::test('account::components.modals.login-qr-code', ['passwordConfirmationToken' => $token])
        ->assertStatus(200);
});

it('aborts without a valid password confirmation token', function () {
    $this->actingAs(makeUser());

    Livewire::test('account::components.modals.login-qr-code')
        ->assertStatus(403);
});

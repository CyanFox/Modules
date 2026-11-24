<?php

use Livewire\Livewire;

it('renders successfully with a confirmed password session', function () {
    $this->actingAs(makeUser());
    $this->withSession(['core.password_confirmed_at' => time()]);
    $token = passwordConfirmationToken();

    Livewire::test('account::components.modals.login-qr-code', ['passwordConfirmationToken' => $token])
        ->assertStatus(200);
});

it('aborts without a confirmed password session', function () {
    $this->actingAs(makeUser());

    Livewire::test('account::components.modals.login-qr-code')
        ->assertStatus(403);
});

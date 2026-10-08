<?php

use Livewire\Livewire;

it('renders successfully', function () {
    Livewire::test('account::components.modals.login-qr-code')
        ->assertStatus(200);
});

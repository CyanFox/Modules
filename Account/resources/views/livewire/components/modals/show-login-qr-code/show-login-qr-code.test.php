<?php

use Livewire\Livewire;

it('renders successfully', function () {
    Livewire::test('account::components.modals.show-login-qr-code')
        ->assertStatus(200);
});

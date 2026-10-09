<?php

use Livewire\Livewire;

it('renders successfully', function () {
    Livewire::test('account::components.passkeys')
        ->assertStatus(200);
});

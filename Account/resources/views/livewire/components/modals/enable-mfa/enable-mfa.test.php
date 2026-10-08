<?php

use Livewire\Livewire;

it('renders successfully', function () {
    Livewire::test('account::components.modals.enable-mfa')
        ->assertStatus(200);
});

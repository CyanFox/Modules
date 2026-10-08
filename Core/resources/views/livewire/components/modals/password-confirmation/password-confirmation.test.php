<?php

use Livewire\Livewire;

it('renders successfully', function () {
    Livewire::test('core::components.modals.password-confirmation')
        ->assertStatus(200);
});

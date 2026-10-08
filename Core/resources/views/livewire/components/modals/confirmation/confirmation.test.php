<?php

use Livewire\Livewire;

it('renders successfully', function () {
    Livewire::test('core::components.modals.confirmation')
        ->assertStatus(200);
});

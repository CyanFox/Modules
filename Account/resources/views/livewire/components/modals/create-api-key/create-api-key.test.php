<?php

use Livewire\Livewire;

it('renders successfully', function () {
    Livewire::test('account::components.modals.create-api-key')
        ->assertStatus(200);
});

<?php

use Livewire\Livewire;

it('renders successfully', function () {
    Livewire::test('account::components.modals.change-avatar')
        ->assertStatus(200);
});

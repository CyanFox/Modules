<?php

use Livewire\Livewire;

it('renders successfully', function () {
    Livewire::test('account::components.modals.activity-details')
        ->assertStatus(200);
});

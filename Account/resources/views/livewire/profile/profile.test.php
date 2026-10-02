<?php

use Livewire\Livewire;

it('renders successfully', function () {
    Livewire::test('account::profile')
        ->assertStatus(200);
});

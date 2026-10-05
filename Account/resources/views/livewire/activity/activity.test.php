<?php

use Livewire\Livewire;

it('renders successfully', function () {
    Livewire::test('account::activity')
        ->assertStatus(200);
});

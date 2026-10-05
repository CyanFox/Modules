<?php

use Livewire\Livewire;

it('renders successfully', function () {
    Livewire::test('account::sessions')
        ->assertStatus(200);
});

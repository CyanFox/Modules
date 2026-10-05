<?php

use Livewire\Livewire;

it('renders successfully', function () {
    Livewire::test('account::api-keys')
        ->assertStatus(200);
});

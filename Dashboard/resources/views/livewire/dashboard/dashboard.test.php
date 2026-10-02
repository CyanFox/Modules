<?php

use Livewire\Livewire;

it('renders successfully', function () {
    Livewire::test('dashboard::dashboard')
        ->assertStatus(200);
});

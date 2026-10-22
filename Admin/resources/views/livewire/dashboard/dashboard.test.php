<?php

use Livewire\Livewire;

it('renders successfully', function () {
    Livewire::test('admin::dashboard')
        ->assertStatus(200);
});

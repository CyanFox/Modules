<?php

use Livewire\Livewire;

it('renders successfully', function () {
    Livewire::test('admin::modules')
        ->assertStatus(200);
});

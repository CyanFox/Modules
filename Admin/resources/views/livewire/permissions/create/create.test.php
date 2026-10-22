<?php

use Livewire\Livewire;

it('renders successfully', function () {
    Livewire::test('admin::permissions.create')
        ->assertStatus(200);
});

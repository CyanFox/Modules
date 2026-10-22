<?php

use Livewire\Livewire;

it('renders successfully', function () {
    Livewire::test('admin::permissions.list')
        ->assertStatus(200);
});

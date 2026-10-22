<?php

use Livewire\Livewire;

it('renders successfully', function () {
    Livewire::test('admin::roles.list')
        ->assertStatus(200);
});

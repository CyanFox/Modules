<?php

use Livewire\Livewire;

it('renders successfully', function () {
    Livewire::test('admin::roles.create')
        ->assertStatus(200);
});

<?php

use Livewire\Livewire;

it('renders successfully', function () {
    Livewire::test('admin::users.create')
        ->assertStatus(200);
});

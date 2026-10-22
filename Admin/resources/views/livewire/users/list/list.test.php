<?php

use Livewire\Livewire;

it('renders successfully', function () {
    Livewire::test('admin::users.list')
        ->assertStatus(200);
});

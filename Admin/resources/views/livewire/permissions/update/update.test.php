<?php

use Livewire\Livewire;

it('renders successfully', function () {
    Livewire::test('admin::permissions.update')
        ->assertStatus(200);
});

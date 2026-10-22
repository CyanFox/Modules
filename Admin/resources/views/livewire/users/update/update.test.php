<?php

use Livewire\Livewire;

it('renders successfully', function () {
    Livewire::test('admin::users.update')
        ->assertStatus(200);
});

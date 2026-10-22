<?php

use Livewire\Livewire;

it('renders successfully', function () {
    Livewire::test('admin::roles.update')
        ->assertStatus(200);
});

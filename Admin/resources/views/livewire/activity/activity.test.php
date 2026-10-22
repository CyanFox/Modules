<?php

use Livewire\Livewire;

it('renders successfully', function () {
    Livewire::test('admin::activity')
        ->assertStatus(200);
});

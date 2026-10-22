<?php

use Livewire\Livewire;

it('renders successfully', function () {
    Livewire::test('admin::settings.general')
        ->assertStatus(200);
});

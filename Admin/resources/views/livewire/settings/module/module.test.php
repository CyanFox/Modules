<?php

use Livewire\Livewire;

it('renders successfully', function () {
    Livewire::test('admin::settings.module')
        ->assertStatus(200);
});

<?php

use Livewire\Livewire;

it('renders successfully', function () {
    Livewire::test('admin::settings.editor')
        ->assertStatus(200);
});

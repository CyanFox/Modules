<?php

use Livewire\Livewire;

it('renders successfully', function () {
    seedCoreSettings();

    $this->actingAs(makeUser());

    Livewire::test('dashboard::dashboard')
        ->assertStatus(200);
});

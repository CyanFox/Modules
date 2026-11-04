<?php

use Livewire\Livewire;

it('renders successfully', function () {
    Livewire::test('auth::auth.register')
        ->assertStatus(200);
});

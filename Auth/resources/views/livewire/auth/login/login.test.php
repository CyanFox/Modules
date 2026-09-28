<?php

use Livewire\Livewire;

it('renders successfully', function () {
    Livewire::test('auth::auth.login')
        ->assertStatus(200);
});

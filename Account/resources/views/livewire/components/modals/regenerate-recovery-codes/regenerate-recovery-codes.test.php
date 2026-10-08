<?php

use Livewire\Livewire;

it('renders successfully', function () {
    Livewire::test('account::components.modals.regenerate-recovery-codes')
        ->assertStatus(200);
});

<?php

use Livewire\Livewire;

beforeEach(fn () => seedCoreSettings());

it('renders successfully', function () {
    $this->actingAs(makeUser([], allAdminPermissions()));

    Livewire::test('admin::settings.module')
        ->assertStatus(200);
});

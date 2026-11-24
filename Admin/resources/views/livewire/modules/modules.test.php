<?php

use Livewire\Livewire;
use Nwidart\Modules\Facades\Module;

beforeEach(fn () => seedCoreSettings());

it('renders successfully', function () {
    $this->actingAs(makeUser([], allAdminPermissions()));

    Livewire::test('admin::modules')
        ->assertStatus(200);
});

it('shows all modules', function () {
    $this->actingAs(makeUser([], allAdminPermissions()));

    foreach (Module::all() as $module) {
        Livewire::test('admin::modules')
            ->assertSee($module->getName());
    }
});

it('does not enable a module without the permission', function () {
    $this->actingAs(makeUser());

    Livewire::test('admin::modules')
        ->call('enableModule', 'Core');

    expect(Module::isEnabled('Core'))->toBeTrue();
});

it('does not disable a module without the permission', function () {
    $this->actingAs(makeUser());

    Livewire::test('admin::modules')
        ->call('disableModule', 'Core');

    expect(Module::isEnabled('Core'))->toBeTrue();
});

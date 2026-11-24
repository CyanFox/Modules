<?php

use Livewire\Livewire;

function confirmationModalArgs(array $overrides = []): array
{
    return array_merge([
        'title' => 'Delete item?',
        'description' => 'This action cannot be undone.',
        'cancel' => 'Cancel',
        'cancelColor' => 'primary',
        'confirm' => 'Confirm',
        'confirmColor' => 'danger',
        'icon' => 'icon-info',
        'iconColor' => 'text-warning',
        'needsPasswordConfirmation' => false,
        'event' => null,
        'cancelEvent' => null,
    ], $overrides);
}

it('renders successfully', function () {
    $this->actingAs(makeUser());

    Livewire::test('core::components.modals.confirmation', confirmationModalArgs())
        ->assertStatus(200)
        ->assertSee('Delete item?');
});

it('dispatches the event on confirm', function () {
    $this->actingAs(makeUser());

    Livewire::test('core::components.modals.confirmation', confirmationModalArgs([
        'event' => 'deleteSomething(1)',
    ]))
        ->call('confirmAction')
        ->assertDispatched('core.confirmation.confirmed', 'deleteSomething(1)')
        ->assertDispatched('closeModal');
});

it('dispatches the cancel event on cancel', function () {
    $this->actingAs(makeUser());

    Livewire::test('core::components.modals.confirmation', confirmationModalArgs([
        'cancelEvent' => 'cancelSomething',
    ]))
        ->call('cancelAction')
        ->assertDispatched('core.confirmation.confirmed', 'cancelSomething')
        ->assertDispatched('closeModal');
});

it('does not confirm without a password confirmed session', function () {
    $this->actingAs(makeUser());

    Livewire::test('core::components.modals.confirmation', confirmationModalArgs([
        'needsPasswordConfirmation' => true,
        'event' => 'deleteSomething(1)',
    ]))
        ->call('confirmAction')
        ->assertNotDispatched('core.confirmation.confirmed');
});

it('confirms with a password confirmed session', function () {
    $this->actingAs(makeUser());

    session(['core.password_confirmed_at' => time()]);

    Livewire::test('core::components.modals.confirmation', confirmationModalArgs([
        'needsPasswordConfirmation' => true,
        'event' => 'deleteSomething(1)',
    ]))
        ->call('confirmAction')
        ->assertDispatched('core.confirmation.confirmed', 'deleteSomething(1)');
});

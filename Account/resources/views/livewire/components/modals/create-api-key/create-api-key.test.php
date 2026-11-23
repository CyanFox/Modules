<?php

use Livewire\Livewire;

it('renders successfully', function () {
    $this->actingAs(makeUser());

    Livewire::test('account::components.modals.create-api-key')
        ->assertStatus(200);
});

it('creates an api key', function () {
    $user = makeUser();
    $this->actingAs($user);

    Livewire::test('account::components.modals.create-api-key')
        ->set('name', 'My API Key')
        ->call('createApiKey');

    expect($user->tokens()->count())->toBe(1);
});

it('shows validation errors without a name', function () {
    $user = makeUser();
    $this->actingAs($user);

    Livewire::test('account::components.modals.create-api-key')
        ->set('name', '')
        ->call('createApiKey')
        ->assertHasErrors('name');

    expect($user->tokens()->count())->toBe(0);
});

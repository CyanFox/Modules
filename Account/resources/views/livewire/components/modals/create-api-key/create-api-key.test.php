<?php

use Livewire\Livewire;

it('renders successfully', function () {
    $this->actingAs(makeUser());
    $this->withSession(['core.password_confirmed_at' => time()]);
    $token = passwordConfirmationToken();

    Livewire::test('account::components.modals.create-api-key', ['passwordConfirmationToken' => $token])
        ->assertStatus(200);
});

it('creates an api key', function () {
    $user = makeUser();
    $this->actingAs($user);
    $this->withSession(['core.password_confirmed_at' => time()]);
    $token = passwordConfirmationToken();

    Livewire::test('account::components.modals.create-api-key', ['passwordConfirmationToken' => $token])
        ->set('name', 'My API Key')
        ->call('createApiKey');

    expect($user->tokens()->count())->toBe(1);
});

it('shows validation errors without a name', function () {
    $user = makeUser();
    $this->actingAs($user);
    $this->withSession(['core.password_confirmed_at' => time()]);
    $token = passwordConfirmationToken();

    Livewire::test('account::components.modals.create-api-key', ['passwordConfirmationToken' => $token])
        ->set('name', '')
        ->call('createApiKey')
        ->assertHasErrors('name');

    expect($user->tokens()->count())->toBe(0);
});

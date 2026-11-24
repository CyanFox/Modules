<?php

use Livewire\Livewire;

it('renders successfully', function () {
    $this->actingAs(makeUser());

    Livewire::test('account::api')
        ->assertStatus(200);
});

it('deletes an api key when confirmed', function () {
    $user = makeUser();
    $user->createToken('Test Token');
    $tokenId = (string) $user->tokens()->first()->id;

    $this->actingAs($user);
    $this->withSession(['core.password_confirmed_at' => time()]);

    Livewire::test('account::api')
        ->call('deleteApiKey', $tokenId, true)
        ->assertRedirect();

    expect($user->tokens()->count())->toBe(0);
});

it('asks for confirmation before deleting an api key', function () {
    $user = makeUser();
    $user->createToken('Test Token');
    $tokenId = (string) $user->tokens()->first()->id;

    $this->actingAs($user);

    Livewire::test('account::api')
        ->call('deleteApiKey', $tokenId)
        ->assertDispatched('openModal', 'core::components.modals.confirmation');

    expect($user->tokens()->count())->toBe(1);
});

<?php

use Livewire\Livewire;

it('renders successfully', function () {
    $this->actingAs(makeUser());

    Livewire::test('account::activity')
        ->assertStatus(200);
});

it('shows only the own activities', function () {
    $user = makeUser();
    $other = makeUser();

    $this->actingAs($user);

    activity()->performedOn($user)->causedBy($user)->log('own.activity');
    activity()->performedOn($other)->causedBy($other)->log('other.activity');

    Livewire::test('account::activity')
        ->set('perPage', 50)
        ->assertSee('own.activity')
        ->assertDontSee('other.activity');
});

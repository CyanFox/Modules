<?php

use Livewire\Livewire;
use Spatie\Activitylog\Models\Activity;

it('shows the details of own activities', function () {
    $user = makeUser(['last_name' => 'Old Name']);

    $this->actingAs($user);

    $user->refresh()->update(['last_name' => 'New Name']);

    $activity = Activity::latest('id')->first();

    $component = Livewire::test('account::components.modals.activity-details', ['activityId' => $activity->id])
        ->assertStatus(200);

    expect($component->get('newValues')['last_name'])->toBe('New Name');
    expect($component->get('oldValues')['last_name'])->toBe('Old Name');
});

it('closes the modal for activities of other users', function () {
    $other = makeUser();

    $this->actingAs($other);

    $other->refresh()->update(['last_name' => 'Other Change']);

    $activity = Activity::latest('id')->first();

    $this->actingAs(makeUser());

    Livewire::test('account::components.modals.activity-details', ['activityId' => $activity->id])
        ->assertDispatched('closeModal');
});

it('closes the modal when the activity does not exist', function () {
    $this->actingAs(makeUser());

    Livewire::test('account::components.modals.activity-details', ['activityId' => 999999])
        ->assertDispatched('closeModal');
});

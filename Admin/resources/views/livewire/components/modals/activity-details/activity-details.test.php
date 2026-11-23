<?php

use Livewire\Livewire;
use Spatie\Activitylog\Models\Activity;

beforeEach(fn () => seedCoreSettings());

it('shows the activity details', function () {
    $admin = makeUser([], allAdminPermissions());
    $target = makeUser(['first_name' => 'Old Name']);

    $this->actingAs($admin);

    $target->update(['first_name' => 'New Name']);

    $activity = Activity::latest('id')->first();

    $component = Livewire::test('admin::components.modals.activity-details', ['activityId' => $activity->id])
        ->assertStatus(200);

    // The User model logs all attributes, so only check the changed one.
    expect($component->get('newValues')['first_name'])->toBe('New Name');
    expect($component->get('oldValues')['first_name'])->toBe('Old Name');
});

it('closes the modal when the activity does not exist', function () {
    $this->actingAs(makeUser([], allAdminPermissions()));

    Livewire::test('admin::components.modals.activity-details', ['activityId' => 999999])
        ->assertDispatched('closeModal');
});

it('aborts without the permission', function () {
    $activity = Activity::create(['description' => 'user.created']);

    $this->actingAs(makeUser());

    Livewire::test('admin::components.modals.activity-details', ['activityId' => $activity->id])
        ->assertStatus(403);
});

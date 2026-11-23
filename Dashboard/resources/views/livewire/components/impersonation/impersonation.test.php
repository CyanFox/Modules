<?php

use Livewire\Livewire;

it('renders successfully', function () {
    $this->actingAs(makeUser());

    Livewire::test('dashboard::components.impersonation')
        ->assertStatus(200);
});

it('leaves the current impersonation', function () {
    $admin = makeUser([], ['core.impersonate']);
    $victim = makeUser();

    $this->actingAs($admin);

    $admin->impersonate($victim);
    expect($admin->isImpersonated())->toBeTrue();

    Livewire::test('dashboard::components.impersonation')
        ->call('leaveImpersonation')
        ->assertRedirect();

    $admin->refresh();

    expect($admin->isImpersonated())->toBeFalse();
});

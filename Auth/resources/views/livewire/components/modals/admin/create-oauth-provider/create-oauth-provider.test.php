<?php

use Livewire\Livewire;
use Modules\Auth\Models\OAuthProvider;

it('renders successfully for users with the permission', function () {
    $this->actingAs(makeUser([], ['admin.settings']));

    Livewire::test('auth::components.modals.admin.create-oauth-provider')
        ->assertStatus(200);
});

it('aborts for users without the permission', function () {
    $this->actingAs(makeUser());

    Livewire::test('auth::components.modals.admin.create-oauth-provider')
        ->assertStatus(403);
});

it('creates a provider', function () {
    $this->actingAs(makeUser([], ['admin.settings']));

    Livewire::test('auth::components.modals.admin.create-oauth-provider')
        ->set('name', 'Google')
        ->set('discoveryUrl', 'https://accounts.google.com/.well-known/openid-configuration')
        ->set('clientId', 'client-id')
        ->set('clientSecret', 'client-secret')
        ->call('createOAuthProvider')
        ->assertRedirect();

    expect(OAuthProvider::where('name', 'Google')->exists())->toBeTrue();
});

it('shows validation errors for incomplete data', function () {
    $this->actingAs(makeUser([], ['admin.settings']));

    Livewire::test('auth::components.modals.admin.create-oauth-provider')
        ->set('name', 'Google')
        ->call('createOAuthProvider')
        ->assertHasErrors(['discoveryUrl', 'clientId', 'clientSecret']);

    expect(OAuthProvider::where('name', 'Google')->exists())->toBeFalse();
});

it('adds and removes field mappings', function () {
    $this->actingAs(makeUser([], ['admin.settings']));

    $component = Livewire::test('auth::components.modals.admin.create-oauth-provider');

    expect($component->get('fields'))->toHaveCount(2);

    $component->call('addField');
    expect($component->get('fields'))->toHaveCount(3);

    $component->call('deleteField', 2);
    expect($component->get('fields'))->toHaveCount(2);
});

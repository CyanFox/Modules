<?php

use Livewire\Livewire;
use Modules\Auth\Models\OAuthProvider;

it('renders successfully', function () {
    $this->actingAs(makeUser([], ['admin.settings']));

    Livewire::test('auth::admin.oauth-providers')
        ->assertStatus(200);
});

it('shows the configured providers', function () {
    $this->actingAs(makeUser([], ['admin.settings']));

    OAuthProvider::create([
        'name' => 'Google',
        'discovery_url' => 'https://accounts.google.com/.well-known/openid-configuration',
        'client_id' => 'client-id',
        'client_secret' => 'client-secret',
        'fields' => [
            ['oauth' => 'email', 'db' => 'email'],
        ],
    ]);

    Livewire::test('auth::admin.oauth-providers')
        ->assertSee('Google');
});

it('deletes a provider when confirmed', function () {
    $this->actingAs(makeUser([], ['admin.settings']));

    $provider = OAuthProvider::create([
        'name' => 'GitHub',
        'discovery_url' => 'https://github.com/login/oauth/openid',
        'client_id' => 'client-id',
        'client_secret' => 'client-secret',
        'fields' => [
            ['oauth' => 'email', 'db' => 'email'],
        ],
    ]);

    Livewire::test('auth::admin.oauth-providers')
        ->call('deleteProvider', $provider->id, true)
        ->assertRedirect();

    expect(OAuthProvider::where('id', $provider->id)->exists())->toBeFalse();
});

it('does not delete a provider without the permission', function () {
    $this->actingAs(makeUser());

    $provider = OAuthProvider::create([
        'name' => 'GitHub',
        'discovery_url' => 'https://github.com/login/oauth/openid',
        'client_id' => 'client-id',
        'client_secret' => 'client-secret',
        'fields' => [
            ['oauth' => 'email', 'db' => 'email'],
        ],
    ]);

    Livewire::test('auth::admin.oauth-providers')
        ->call('deleteProvider', $provider->id, true);

    expect(OAuthProvider::where('id', $provider->id)->exists())->toBeTrue();
});

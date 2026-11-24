<?php

use Livewire\Livewire;
use Modules\Auth\Models\OAuthProvider;

function makeOAuthProviderForTest(array $attributes = []): OAuthProvider
{
    $provider = new OAuthProvider(array_merge([
        'name' => 'Google',
        'discovery_url' => 'https://accounts.google.com/.well-known/openid-configuration',
        'client_id' => 'client-id',
        'client_secret' => 'client-secret',
        'account_linking' => true,
        'fields' => [
            ['oauth' => 'email', 'db' => 'email'],
        ],
    ], $attributes));

    $provider->save();

    return $provider;
}

it('renders successfully for users with the permission', function () {
    $this->actingAs(makeUser([], ['admin.settings']));

    $provider = makeOAuthProviderForTest();

    Livewire::test('auth::components.modals.admin.update-oauth-provider', ['providerId' => $provider->id])
        ->assertStatus(200)
        ->assertSet('name', 'Google');
});

it('aborts for users without the permission', function () {
    $this->actingAs(makeUser());

    $provider = makeOAuthProviderForTest();

    Livewire::test('auth::components.modals.admin.update-oauth-provider', ['providerId' => $provider->id])
        ->assertStatus(403);
});

it('aborts when the provider does not exist', function () {
    $this->actingAs(makeUser([], ['admin.settings']));

    Livewire::test('auth::components.modals.admin.update-oauth-provider', ['providerId' => 999999])
        ->assertStatus(404);
});

it('updates a provider', function () {
    $this->actingAs(makeUser([], ['admin.settings']));

    $provider = makeOAuthProviderForTest();

    Livewire::test('auth::components.modals.admin.update-oauth-provider', ['providerId' => $provider->id])
        ->set('name', 'GitHub')
        ->set('discoveryUrl', 'https://github.com/login/oauth/openid')
        ->set('clientId', 'new-client-id')
        ->set('clientSecret', 'new-client-secret')
        ->call('updateOAuthProvider')
        ->assertRedirect();

    $provider->refresh();

    expect($provider->name)->toBe('GitHub');
    expect($provider->client_id)->toBe('new-client-id');
});

it('shows validation errors for incomplete data', function () {
    $this->actingAs(makeUser([], ['admin.settings']));

    $provider = makeOAuthProviderForTest();

    Livewire::test('auth::components.modals.admin.update-oauth-provider', ['providerId' => $provider->id])
        ->set('name', '')
        ->call('updateOAuthProvider')
        ->assertHasErrors('name');
});

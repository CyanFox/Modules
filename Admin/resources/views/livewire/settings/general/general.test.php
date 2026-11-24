<?php

use Livewire\Livewire;

beforeEach(fn () => seedCoreSettings());

it('renders with the current settings', function () {
    $this->actingAs(makeUser([], allAdminPermissions()));

    Livewire::test('admin::settings.general')
        ->assertStatus(200)
        ->assertSet('name', settings('app.name'))
        ->assertSet('url', settings('app.url'))
        ->assertSet('timezone', settings('app.timezone'))
        ->assertSet('language', settings('app.locale'));
});

it('updates the settings', function () {
    $this->actingAs(makeUser([], allAdminPermissions()));

    Livewire::test('admin::settings.general')
        ->set('name', 'Renamed App')
        ->set('url', 'https://example.com')
        ->set('timezone', 'Europe/Berlin')
        ->set('language', 'de')
        ->call('updateSettings')
        ->assertRedirect();

    expect(settings('app.name'))->toBe('Renamed App');
    expect(settings('app.url'))->toBe('https://example.com');
    expect(settings('app.timezone'))->toBe('Europe/Berlin');
    expect(settings('app.locale'))->toBe('de');
});

it('shows validation errors for an invalid url', function () {
    $this->actingAs(makeUser([], allAdminPermissions()));

    Livewire::test('admin::settings.general')
        ->set('url', 'not-a-url')
        ->call('updateSettings')
        ->assertHasErrors('url');

    expect(settings('app.url'))->toBe('http://localhost');
});

it('shows validation errors for a missing name', function () {
    $this->actingAs(makeUser([], allAdminPermissions()));

    Livewire::test('admin::settings.general')
        ->set('name', '')
        ->call('updateSettings')
        ->assertHasErrors('name');
});

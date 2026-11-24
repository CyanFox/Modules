<?php

use Livewire\Livewire;
use Modules\Core\Models\Setting;

beforeEach(fn () => seedCoreSettings());

it('renders successfully', function () {
    $this->actingAs(makeUser([], allAdminPermissions()));

    Livewire::test('admin::settings.editor')
        ->assertStatus(200);
});

it('loads all settings', function () {
    $this->actingAs(makeUser([], allAdminPermissions()));

    $component = Livewire::test('admin::settings.editor');

    expect($component->get('settings'))->toHaveCount(Setting::count());
});

it('updates setting values', function () {
    $this->actingAs(makeUser([], allAdminPermissions()));

    $setting = Setting::where('key', 'app.name')->first();

    Livewire::test('admin::settings.editor')
        ->set("settings.{$setting->id}.value", 'Renamed App')
        ->call('updateSettings')
        ->assertRedirect();

    expect(settings('app.name'))->toBe('Renamed App');
});

it('shows a warning when a setting value fails its rules', function () {
    $this->actingAs(makeUser([], allAdminPermissions()));

    $setting = Setting::where('key', 'app.url')->first();

    Livewire::test('admin::settings.editor')
        ->set("settings.{$setting->id}.value", 'not-a-url')
        ->call('updateSettings');

    expect(settings('app.url'))->toBe('http://localhost');
});

it('deletes a setting when confirmed', function () {
    $this->actingAs(makeUser([], allAdminPermissions()));

    $setting = Setting::where('key', 'app.logo')->first();

    Livewire::test('admin::settings.editor')
        ->call('deleteSetting', $setting->id, true)
        ->assertRedirect();

    expect(Setting::find($setting->id))->toBeNull();
});

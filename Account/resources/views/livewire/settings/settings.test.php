<?php

use Livewire\Livewire;
use Modules\Core\Enums\SettingsProperty;
use Modules\Core\Models\UserSetting;

it('renders successfully', function () {
    $this->actingAs(makeUser());

    Livewire::test('account::settings')
        ->assertStatus(200);
});

it('loads all settings', function () {
    $this->actingAs(makeUser());

    UserSetting::create([
        'user_id' => auth()->id(),
        'key' => 'app.name',
        'value' => 'Test App',
    ]);

    $component = Livewire::test('account::settings');

    expect($component->get('settings'))->toHaveCount(UserSetting::where('user_id', auth()->id())->count());
});

it('updates setting values', function () {
    $this->actingAs(makeUser([], allAdminPermissions()));

    $setting = UserSetting::create([
        'user_id' => auth()->id(),
        'key' => 'app.name',
        'value' => 'Test App',
    ]);

    Livewire::test('account::settings')
        ->set("settings.{$setting->id}.value", 'Renamed App')
        ->call('updateSettings')
        ->assertRedirect();

    expect(UserSetting::where('user_id', auth()->id())->where('key', 'app.name')->first()->value)->toBe('Renamed App');
});

it('shows a warning when a setting value fails its rules', function () {
    $this->actingAs(makeUser([], allAdminPermissions()));

    $setting = UserSetting::create([
        'user_id' => auth()->id(),
        'key' => 'app.url',
        'value' => 'http://localhost',
        'properties' => [SettingsProperty::RULES => ['url']],
    ]);

    Livewire::test('account::settings')
        ->set("settings.{$setting->id}.value", 'not-a-url')
        ->call('updateSettings');

    expect(UserSetting::where('user_id', auth()->id())->where('key', 'app.url')->first()->value)->toBe('http://localhost');
});

it('deletes a setting when confirmed', function () {
    $this->actingAs(makeUser([], allAdminPermissions()));

    $setting = UserSetting::create([
        'user_id' => auth()->id(),
        'key' => 'app.name',
        'value' => 'Test App',
    ]);

    Livewire::test('account::settings')
        ->call('deleteSetting', $setting->id, true)
        ->assertRedirect();

    expect(UserSetting::find($setting->id))->toBeNull();
});

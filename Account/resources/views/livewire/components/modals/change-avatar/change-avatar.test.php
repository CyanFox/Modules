<?php

use Illuminate\Http\UploadedFile;
use Livewire\Livewire;

beforeEach(function () {
    seedCoreSettings();
    setSetting('account.enable.change_avatar', true);
});

it('aborts when changing the avatar is disabled', function () {
    setSetting('account.enable.change_avatar', false);

    $this->actingAs(makeUser());

    Livewire::test('account::components.modals.change-avatar')
        ->assertStatus(403);
});

it('renders successfully', function () {
    $this->actingAs(makeUser());

    Livewire::test('account::components.modals.change-avatar')
        ->assertStatus(200);
});

it('sets a custom avatar url', function () {
    $user = makeUser();
    $this->actingAs($user);

    Livewire::test('account::components.modals.change-avatar')
        ->set('customAvatarUrl', 'https://example.com/avatar.png')
        ->call('changeAvatar')
        ->assertRedirect();

    expect(userSettings('core.custom_avatar_url', $user->id))->toBe('https://example.com/avatar.png');
});

it('uploads a new avatar', function () {
    $user = makeUser();
    $this->actingAs($user);

    Livewire::test('account::components.modals.change-avatar')
        ->set('avatar', UploadedFile::fake()->image('avatar.png'))
        ->call('changeAvatar')
        ->assertRedirect();

    $user->refresh();

    expect($user->hasMedia('avatar'))->toBeTrue();
});

it('resets the avatar', function () {
    $user = makeUser();
    userSettings()->set($user->id, 'core.custom_avatar_url', 'https://example.com/avatar.png', true);
    $this->actingAs($user);

    Livewire::test('account::components.modals.change-avatar')
        ->call('resetAvatar')
        ->assertRedirect();

    expect(userSettings('core.custom_avatar_url', $user->id))->toBeNull();
});

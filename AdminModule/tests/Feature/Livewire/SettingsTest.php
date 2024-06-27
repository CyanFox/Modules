<?php

namespace Modules\AdminModule\Tests\Feature\Livewire;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Modules\AdminModule\Livewire\Settings;
use Modules\AdminModule\Tests\TestCase;
use Modules\AuthModule\Facades\UserManager;
use Modules\AuthModule\Models\User;
use PHPUnit\Framework\Attributes\Test;

class SettingsTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function renders_successfully()
    {
        $user = User::factory()->create();
        UserManager::getUser($user)->getTwoFactorManager()->generateTwoFactorSecret();
        $user->assignRole('Super Admin');

        $this->actingAs($user)->get(route('admin.settings'))
            ->assertStatus(200);

        $this->actingAs($user)->get(route('admin.settings', ['tab' => __('adminmodule::settings.tabs.modules')]))
            ->assertStatus(200);

        $this->actingAs($user)->get(route('admin.settings', ['tab' => __('adminmodule::settings.tabs.editor')]))
            ->assertStatus(200);
    }

    #[Test]
    public function can_update_settings()
    {
        $user = User::factory()->create();
        UserManager::getUser($user)->getTwoFactorManager()->generateTwoFactorSecret();
        $user->assignRole('Super Admin');

        Livewire::actingAs($user)
            ->test(Settings::class)
            ->set('systemName', 'Test')
            ->set('systemUrl', 'http://127.0.0.1')
            ->set('systemLang', 'en')
            ->set('systemTimeZone', 'UTC')
            ->set('unsplashUtm', 'test')
            ->set('unsplashApiKey', 'not_a_real_key')
            ->set('projectVersionUrl', 'https://raw.githubusercontent.com/CyanFox-Projects/Laravel-Template/v3/version.json')
            ->set('templateVersionUrl', 'https://raw.githubusercontent.com/CyanFox-Projects/Laravel-Template/v3/version.json')
            ->call('updateSystemSettings');

        $this->assertDatabaseHas('settings', [
            'key' => 'settings.name',
            'value' => 'Test',
        ]);

    }

    #[Test]
    public function can_update_editor()
    {
        $user = User::factory()->create();
        UserManager::getUser($user)->getTwoFactorManager()->generateTwoFactorSecret();
        $user->assignRole('Super Admin');

        Livewire::actingAs($user)
            ->test(Settings::class)
            ->set('editorSettings', ['test' => 'test'])
            ->call('updateEditorSettings');

        $this->assertDatabaseHas('settings', [
            'key' => 'test',
            'value' => 'test',
        ]);
    }
}

<?php

namespace Feature\Livewire;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Modules\Admin\Livewire\Settings\Settings;
use Modules\Admin\tests\TestCase;
use Modules\Auth\Models\User;
use PHPUnit\Framework\Attributes\Test;

class SettingsTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function renders_successfully()
    {
        $user = User::factory()->create();
        $user->assignRole('Super Admin');

        $this->actingAs($user)->get(route('admin.settings'))
            ->assertStatus(200);

        $this->actingAs($user)->get(route('admin.settings', ['tab' => 'modules']))
            ->assertStatus(200);

        $this->actingAs($user)->get(route('admin.settings', ['tab' => 'editor']))
            ->assertStatus(200);
    }

    #[Test]
    public function can_update_settings()
    {
        $user = User::factory()->create();
        $user->assignRole('Super Admin');

        Livewire::actingAs($user)
            ->test(Settings::class)
            ->set('appName', 'Test')
            ->set('appUrl', 'http://127.0.0.1')
            ->set('appLanguage', 'en')
            ->set('appTimezone', 'UTC')
            ->set('baseVersionUrl', 'https://raw.githubusercontent.com/CyanFox-Labs/CyanFox-Base/v4/version.json')
            ->call('updateGeneralSettings');

        $this->assertDatabaseHas('settings', [
            'key' => 'internal.app.name',
            'value' => 'Test',
        ]);

    }

    #[Test]
    public function can_update_editor()
    {
        $user = User::factory()->create();
        $user->assignRole('Super Admin');

        $settingKey = 'internal.app.name';
        $formKey = 'setting_'.md5($settingKey);

        Livewire::actingAs($user)
            ->test(Settings::class)
            ->set('editorSettings', [$formKey => 'Test-App'])
            ->set('editorSettingsMap', [$formKey => $settingKey])
            ->call('updateEditorSettings');

        $this->assertDatabaseHas('settings', [
            'key' => 'internal.app.name',
            'value' => 'Test-App',
        ]);
    }
}

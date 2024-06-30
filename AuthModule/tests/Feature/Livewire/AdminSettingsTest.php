<?php

namespace Feature\Livewire;

use App\Facades\ModuleManager;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Livewire\Livewire;
use Modules\AuthModule\Facades\UserManager;
use Modules\AuthModule\Livewire\AdminSettings;
use Modules\AuthModule\Models\User;
use PHPUnit\Framework\Attributes\Test;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class AdminSettingsTest extends TestCase
{
    use RefreshDatabase;

    protected function setPermissions(): void
    {
        $permissions = [
            'adminmodule.admin',
            'adminmodule.dashboard.view',
            'adminmodule.users.view',
            'adminmodule.users.create',
            'adminmodule.users.update',
            'adminmodule.users.delete',
            'adminmodule.groups.view',
            'adminmodule.groups.create',
            'adminmodule.groups.update',
            'adminmodule.groups.delete',
            'adminmodule.permissions.view',
            'adminmodule.permissions.create',
            'adminmodule.permissions.update',
            'adminmodule.permissions.delete',
            'adminmodule.settings.view',
            'adminmodule.settings.update',
            'adminmodule.settings.editor',
            'adminmodule.settings.editor.update',
            'adminmodule.modules.view',
            'adminmodule.modules.disable',
            'adminmodule.modules.enable',
            'adminmodule.modules.install',
            'adminmodule.modules.delete',
            'adminmodule.modules.actions.npm',
            'adminmodule.modules.actions.composer',
            'adminmodule.modules.actions.migrate',
        ];

        $existingPermissionsQuery = Permission::query();
        $existingPermissions = $existingPermissionsQuery->whereIn('name', $permissions)->get()->keyBy('name');
        $newPermissions = [];

        foreach ($permissions as $permission) {
            if (!$existingPermissions->has($permission)) {
                $newPermissions[] = ['name' => $permission, 'module' => 'adminmodule'];
            }
        }

        if (!empty($newPermissions)) {
            Permission::insert($newPermissions);
        }

        $role = Role::create(['name' => 'Super Admin'])->first();
        $role->syncPermissions(
            Permission::all()
        );
    }

    #[Test]
    public function renders_successfully()
    {
        if (ModuleManager::getModule('AdminModule')->isDisabled()) {
            $this->markTestSkipped('AdminModule is not enabled.');
        }
        $this->setPermissions();
        $user = User::factory()->create();
        UserManager::getUser($user)->getTwoFactorManager()->generateTwoFactorSecret();
        $user->assignRole('Super Admin');

        $this->actingAs($user)->get(route('admin.modules.settings.authmodule'))
            ->assertStatus(200);
    }

    #[Test]
    public function can_update_settings()
    {
        if (ModuleManager::getModule('AdminModule')->isDisabled()) {
            $this->markTestSkipped('AdminModule is not enabled.');
        }
        $this->setPermissions();

        $user = User::factory()->create([
            'password' => Hash::make('password'),
        ]);
        UserManager::getUser($user)->getTwoFactorManager()->generateTwoFactorSecret();
        $user->assignRole('Super Admin');

        Livewire::actingAs($user)
            ->test(AdminSettings::class)
            ->set('enableCaptcha', true)
            ->set('enableRegister', true)
            ->set('enableForgotPassword', true)
            ->set('forgotPasswordEmailTitle', 'Forgot Password')
            ->set('forgotPasswordEmailSubject', 'Forgot Password')
            ->set('forgotPasswordEmailContent', 'Forgot Password')
            ->set('defaultAvatarUrl', 'https://example.com/avatar.png')
            ->set('allowChangeAvatar', true)
            ->set('allowDeleteAccount', true)
            ->set('passwordMinLength', 8)
            ->set('passwordBlacklist', 'password')
            ->set('passwordRequireNumber', true)
            ->set('passwordRequireSpecialCharacter', true)
            ->set('passwordRequireUppercase', true)
            ->set('passwordRequireLowercase', true)
            ->call('updateAuthSettings')
            ->call('updateAccountSettings')
            ->call('updateEmailSettings');

        $this->assertDatabaseHas('settings', [
            'key' => 'authmodule.enable.captcha',
            'value' => '1',
        ]);

        $this->assertDatabaseHas('settings', [
            'key' => 'authmodule.emails.forgot_password.title',
            'value' => 'Forgot Password',
        ]);

        $this->assertDatabaseHas('settings', [
            'key' => 'authmodule.password.minimum_length',
            'value' => '8',
        ]);
    }
}

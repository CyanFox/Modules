<?php

namespace Modules\AdminModule\Tests\Feature\Livewire;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Modules\AdminModule\Livewire\Components\Tables\PermissionsTable;
use Modules\AdminModule\Livewire\Permissions\CreatePermission;
use Modules\AdminModule\Livewire\Permissions\UpdatePermission;
use Modules\AdminModule\Tests\TestCase;
use Modules\AuthModule\Facades\UserManager;
use Modules\AuthModule\Models\User;
use PHPUnit\Framework\Attributes\Test;
use Spatie\Permission\Models\Permission;

class PermissionsTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function renders_successfully()
    {
        $user = User::factory()->create();
        UserManager::getUser($user)->getTwoFactorManager()->generateTwoFactorSecret();
        $user->assignRole('Super Admin');

        $this->actingAs($user)->get(route('admin.permissions'))
            ->assertStatus(200);
    }

    #[Test]
    public function can_create_permission()
    {
        $user = User::factory()->create();
        UserManager::getUser($user)->getTwoFactorManager()->generateTwoFactorSecret();
        $user->assignRole('Super Admin');

        Livewire::actingAs($user)
            ->test(CreatePermission::class)
            ->set('name', 'Test')
            ->set('guardName', 'web')
            ->set('module', 'test')
            ->call('createPermission');

        $this->assertDatabaseHas('permissions', [
            'name' => 'Test',
            'guard_name' => 'web',
            'module' => 'test',
        ]);

    }

    #[Test]
    public function can_update_permission()
    {
        $user = User::factory()->create();
        UserManager::getUser($user)->getTwoFactorManager()->generateTwoFactorSecret();
        $user->assignRole('Super Admin');

        $permissionToUpdate = Permission::create(['name' => 'Test1', 'guard_name' => 'web', 'module' => 'test1']);

        Livewire::actingAs($user)
            ->test(UpdatePermission::class, ['permissionId' => $permissionToUpdate->id])
            ->set('permissionId', $permissionToUpdate->id)
            ->set('name', 'test')
            ->set('guardName', 'web')
            ->set('module', 'test')
            ->call('updatePermission');

        $this->assertDatabaseHas('permissions', [
            'id' => $permissionToUpdate->id,
            'name' => 'test',
            'guard_name' => 'web',
            'module' => 'test',
        ]);
    }
}

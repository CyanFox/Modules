<?php

namespace Modules\Admin\Tests\Feature\Livewire;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Modules\Admin\Livewire\Components\Tables\PermissionsTable;
use Modules\Admin\Livewire\Permissions\CreatePermission;
use Modules\Admin\Livewire\Permissions\UpdatePermission;
use Modules\Admin\tests\TestCase;
use Modules\Auth\Models\User;
use PHPUnit\Framework\Attributes\Test;
use Spatie\Permission\Models\Permission;

class PermissionsTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function renders_successfully()
    {
        $user = User::factory()->create();
        $user->assignRole('Super Admin');

        $this->actingAs($user)->get(route('admin.permissions'))
            ->assertStatus(200);
    }

    #[Test]
    public function can_create_permission()
    {
        $user = User::factory()->create();
        $user->assignRole('Super Admin');

        Livewire::actingAs($user)
            ->test(CreatePermission::class)
            ->set('name', 'Test')
            ->set('guardName', 'web')
            ->call('createPermission');

        $this->assertDatabaseHas('permissions', [
            'name' => 'Test',
            'guard_name' => 'web',
        ]);
    }

    #[Test]
    public function can_update_permission()
    {
        $user = User::factory()->create();
        $user->assignRole('Super Admin');

        $permissionToUpdate = Permission::create(['name' => 'Test1', 'guard_name' => 'web']);

        Livewire::actingAs($user)
            ->test(UpdatePermission::class, ['permissionId' => $permissionToUpdate->id])
            ->set('permissionId', $permissionToUpdate->id)
            ->set('name', 'test')
            ->set('guardName', 'web')
            ->call('updatePermission');

        $this->assertDatabaseHas('permissions', [
            'id' => $permissionToUpdate->id,
            'name' => 'test',
            'guard_name' => 'web',
        ]);
    }

    #[Test]
    public function can_delete_permission()
    {
        $user = User::factory()->create();
        $user->assignRole('Super Admin');

        $permissionToDelete = Permission::create(['name' => 'Test1', 'guard_name' => 'web']);

        Livewire::actingAs($user)
            ->test(PermissionsTable::class)
            ->call('deletePermission', $permissionToDelete->id);

        $this->assertDatabaseMissing('permissions', [
            'id' => $permissionToDelete->id,
        ]);
    }
}

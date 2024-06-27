<?php

namespace Modules\AdminModule\Tests\Feature\Livewire;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Modules\AdminModule\Livewire\Groups;
use Modules\AdminModule\Tests\TestCase;
use Modules\AuthModule\Facades\UserManager;
use Modules\AuthModule\Models\User;
use PHPUnit\Framework\Attributes\Test;
use Spatie\Permission\Models\Role;

class GroupsTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function renders_successfully()
    {
        $user = User::factory()->create();
        UserManager::getUser($user)->getTwoFactorManager()->generateTwoFactorSecret();
        $user->assignRole('Super Admin');

        $this->actingAs($user)->get(route('admin.groups'))
            ->assertStatus(200);
    }

    #[Test]
    public function can_create_group()
    {
        $user = User::factory()->create();
        UserManager::getUser($user)->getTwoFactorManager()->generateTwoFactorSecret();
        $user->assignRole('Super Admin');

        Livewire::actingAs($user)
            ->test(Groups::class)
            ->set('name', 'Test')
            ->set('guardName', 'web')
            ->set('module', 'test')
            ->set('permissions', [])
            ->call('createGroup');

        $this->assertDatabaseHas('roles', [
            'name' => 'Test',
            'guard_name' => 'web',
            'module' => 'test',
        ]);

    }

    #[Test]
    public function can_update_group()
    {
        $user = User::factory()->create();
        UserManager::getUser($user)->getTwoFactorManager()->generateTwoFactorSecret();
        $user->assignRole('Super Admin');

        $groupToUpdate = Role::create(['name' => 'Test1', 'guard_name' => 'web', 'module' => 'test1']);

        Livewire::actingAs($user)
            ->test(Groups::class)
            ->set('groupId', $groupToUpdate->id)
            ->set('name', 'test')
            ->set('guardName', 'web')
            ->set('module', 'test')
            ->call('updateGroup');

        $this->assertDatabaseHas('roles', [
            'id' => $groupToUpdate->id,
            'name' => 'test',
            'guard_name' => 'web',
            'module' => 'test',
        ]);
    }
}

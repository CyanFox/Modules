<?php

namespace Feature\Livewire;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Livewire\Livewire;
use Modules\Admin\Livewire\Components\Tables\AnnouncementsTable;
use Modules\Admin\Livewire\Components\Tables\GroupsTable;
use Modules\Admin\Livewire\Groups\CreateGroup;
use Modules\Admin\Livewire\Groups\Groups;
use Modules\Admin\Livewire\Groups\UpdateGroup;
use Modules\Admin\tests\TestCase;
use Modules\Auth\Models\User;
use PHPUnit\Framework\Attributes\Test;
use Spatie\Permission\Models\Role;

class GroupsTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function renders_successfully()
    {
        $user = User::factory()->create();
        $user->assignRole('Super Admin');

        $this->actingAs($user)->get(route('admin.groups'))
            ->assertStatus(200);
    }

    #[Test]
    public function can_create_group()
    {
        $user = User::factory()->create();
        $user->assignRole('Super Admin');

        Livewire::actingAs($user)
            ->test(CreateGroup::class)
            ->set('name', 'Test')
            ->set('guardName', 'web')
            ->set('permissions', [])
            ->call('createGroup');

        $this->assertDatabaseHas('roles', [
            'name' => 'Test',
            'guard_name' => 'web',
        ]);

    }

    #[Test]
    public function can_update_group()
    {
        $user = User::factory()->create();
        $user->assignRole('Super Admin');

        $groupToUpdate = Role::create(['name' => 'Test1', 'guard_name' => 'web']);

        Livewire::actingAs($user)
            ->test(UpdateGroup::class, ['groupId' => $groupToUpdate->id])
            ->set('groupId', $groupToUpdate->id)
            ->set('name', 'test')
            ->set('guardName', 'web')
            ->call('updateGroup');

        $this->assertDatabaseHas('roles', [
            'id' => $groupToUpdate->id,
            'name' => 'test',
            'guard_name' => 'web',
        ]);
    }

    #[Test]
    public function can_delete_group()
    {
        $user = User::factory()->create();
        $user->assignRole('Super Admin');

        $groupToDelete = Role::create(['name' => 'Test1', 'guard_name' => 'web']);

        Livewire::actingAs($user)
            ->test(GroupsTable::class)
            ->call('deleteGroup', $groupToDelete->id);

        $this->assertDatabaseMissing('roles', [
            'id' => $groupToDelete->id,
        ]);
    }
}

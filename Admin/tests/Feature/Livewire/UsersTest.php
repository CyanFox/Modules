<?php

namespace Feature\Livewire;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Modules\Admin\Livewire\Components\Tables\UsersTable;
use Modules\Admin\Livewire\Users\CreateUser;
use Modules\Admin\Livewire\Users\UpdateUser;
use Modules\Admin\tests\TestCase;
use Modules\Auth\Models\User;
use PHPUnit\Framework\Attributes\Test;

class UsersTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function renders_successfully()
    {
        $user = User::factory()->create();
        $user->assignRole('Super Admin');

        $this->actingAs($user)->get(route('admin.users'))
            ->assertStatus(200);
    }

    #[Test]
    public function can_create_user()
    {
        $user = User::factory()->create();
        $user->assignRole('Super Admin');

        Livewire::actingAs($user)
            ->test(CreateUser::class)
            ->set('firstName', 'Test')
            ->set('lastName', 'User')
            ->set('username', 'test')
            ->set('email', 'test@local.host')
            ->set('password', 'kXqz=k^zwu7d^;UrMPNF')
            ->set('confirmPassword', 'kXqz=k^zwu7d^;UrMPNF')
            ->set('groups', [])
            ->set('permissions', [])
            ->set('forceActivateTwoFactor', false)
            ->set('forceChangePassword', false)
            ->set('disabled', false)
            ->call('createUser');

        $this->assertDatabaseHas('users', [
            'username' => 'test',
            'first_name' => 'Test',
            'last_name' => 'User',
            'email' => 'test@local.host',
        ]);

    }

    #[Test]
    public function can_update_user()
    {
        $user = User::factory()->create();
        $user->assignRole('Super Admin');

        $userToUpdate = User::factory()->create();

        Livewire::actingAs($user)
            ->test(UpdateUser::class, ['userId' => $userToUpdate->id])
            ->set('userId', $userToUpdate->id)
            ->set('firstName', 'Test')
            ->set('lastName', 'User')
            ->set('username', 'test')
            ->set('email', 'test@local.host')
            ->set('password', 'kXqz=k^zwu7d^;UrMPNF')
            ->set('confirmPassword', 'kXqz=k^zwu7d^;UrMPNF')
            ->set('groups', [])
            ->set('permissions', [])
            ->set('forceActivateTwoFactor', false)
            ->set('forceChangePassword', false)
            ->set('disabled', false)
            ->call('updateUser');

        $this->assertDatabaseHas('users', [
            'id' => $userToUpdate->id,
            'username' => 'test',
            'first_name' => 'Test',
            'last_name' => 'User',
            'email' => 'test@local.host',
        ]);
    }

    #[Test]
    public function can_delete_user()
    {
        $user = User::factory()->create();
        $user->assignRole('Super Admin');

        $userToDelete = User::factory()->create();

        Livewire::actingAs($user)
            ->test(UsersTable::class)
            ->call('deleteUser', $userToDelete->id);

        $this->assertDatabaseMissing('users', [
            'id' => $userToDelete->id,
        ]);
    }
}

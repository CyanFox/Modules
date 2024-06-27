<?php

namespace Feature\Livewire;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Modules\AdminModule\Livewire\Users;
use Modules\AdminModule\Tests\TestCase;
use Modules\AuthModule\Facades\UserManager;
use Modules\AuthModule\Models\User;
use PHPUnit\Framework\Attributes\Test;

class UsersTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function renders_successfully()
    {
        $user = User::factory()->create();
        UserManager::getUser($user)->getTwoFactorManager()->generateTwoFactorSecret();
        $user->assignRole('Super Admin');

        $this->actingAs($user)->get(route('admin.users'))
            ->assertStatus(200);
    }

    #[Test]
    public function can_create_user()
    {
        $user = User::factory()->create();
        UserManager::getUser($user)->getTwoFactorManager()->generateTwoFactorSecret();
        $user->assignRole('Super Admin');

        Livewire::actingAs($user)
            ->test(Users::class)
            ->set('firstName', 'Test')
            ->set('lastName', 'User')
            ->set('username', 'test')
            ->set('email', 'test@local.host')
            ->set('password', 'kXqz=k^zwu7d^;UrMPNF')
            ->set('passwordConfirmation', 'kXqz=k^zwu7d^;UrMPNF')
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
        UserManager::getUser($user)->getTwoFactorManager()->generateTwoFactorSecret();
        $user->assignRole('Super Admin');

        $userToUpdate = User::factory()->create();

        Livewire::actingAs($user)
            ->test(Users::class)
            ->set('userId', $userToUpdate->id)
            ->set('firstName', 'Test')
            ->set('lastName', 'User')
            ->set('username', 'test')
            ->set('email', 'test@local.host')
            ->set('password', 'kXqz=k^zwu7d^;UrMPNF')
            ->set('passwordConfirmation', 'kXqz=k^zwu7d^;UrMPNF')
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
}

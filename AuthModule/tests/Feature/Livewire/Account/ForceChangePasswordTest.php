<?php

namespace Modules\AuthModule\Tests\Feature\Livewire\Account;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Livewire\Livewire;
use Modules\AuthModule\Facades\UserManager;
use Modules\AuthModule\Livewire\Account\ForceChangePassword;
use Modules\AuthModule\Models\User;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class ForceChangePasswordTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function renders_successfully()
    {
        $user = User::factory()->create();
        UserManager::getUser($user)->getTwoFactorManager()->generateTwoFactorSecret();

        $this->actingAs($user)->get(route('account.force-change-password'))
            ->assertStatus(200);
    }

    #[Test]
    public function can_change_password()
    {
        $user = User::factory()->create([
            'password' => Hash::make('password'),
        ]);
        UserManager::getUser($user)->getTwoFactorManager()->generateTwoFactorSecret();

        Livewire::actingAs($user)
            ->test(ForceChangePassword::class)
            ->set('currentPassword', 'password')
            ->set('newPassword', 'kXqz=k^zwu7d^;UrMPNF')
            ->set('newPasswordConfirmation', 'kXqz=k^zwu7d^;UrMPNF')
            ->call('updatePassword');

        $this->assertTrue(Hash::check('kXqz=k^zwu7d^;UrMPNF', $user->fresh()->password));

    }
}

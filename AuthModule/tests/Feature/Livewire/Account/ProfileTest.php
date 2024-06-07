<?php

namespace Modules\AuthModule\Tests\Feature\Livewire\Account;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Livewire\Livewire;
use Modules\AuthModule\Facades\UserManager;
use Modules\AuthModule\Livewire\Account\Profile;
use Modules\AuthModule\Models\User;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class ProfileTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function renders_successfully()
    {
        $user = User::factory()->create();
        UserManager::getUser($user)->getTwoFactorManager()->generateTwoFactorSecret();

        $this->actingAs($user)->get(route('account.profile'))
            ->assertStatus(200);

        $this->actingAs($user)->get(route('account.profile', ['tab' => 'sessions']))
            ->assertStatus(200);

        $this->actingAs($user)->get(route('account.profile', ['tab' => 'apiKeys']))
            ->assertStatus(200);

        $this->actingAs($user)->get(route('account.profile', ['tab' => 'activity']))
            ->assertStatus(200);
    }

    #[Test]
    public function can_update_profile()
    {
        $user = User::factory()->create([
            'password' => Hash::make('password'),
        ]);
        UserManager::getUser($user)->getTwoFactorManager()->generateTwoFactorSecret();

        Livewire::actingAs($user)
            ->test(Profile::class)
            ->set('username', 'test')
            ->set('firstName', 'Test')
            ->set('lastName', 'User')
            ->set('email', 'test@local.host')
            ->set('theme', 'dark')
            ->set('language', 'de')
            ->set('currentPassword', 'password')
            ->set('newPassword', 'kXqz=k^zwu7d^;UrMPNF')
            ->set('newPasswordConfirmation', 'kXqz=k^zwu7d^;UrMPNF')
            ->call('updateProfile')
            ->call('updatePassword')
            ->call('updateLanguageAndTheme');

        $this->assertDatabaseHas('users', [
            'username' => 'test',
            'first_name' => 'Test',
            'last_name' => 'User',
            'email' => 'test@local.host',
            'theme' => 'dark',
            'language' => 'de',
        ]);

    }
}

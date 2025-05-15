<?php

namespace Modules\Auth\Tests\Feature\Livewire\Account;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Livewire\Livewire;
use Modules\Auth\Livewire\Account\Profile;
use Modules\Auth\Livewire\Auth\Login;
use Modules\Auth\Models\User;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class ProfileTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function renders_successfully()
    {
        $user = User::factory()->create();
        $user->generateTwoFASecret();

        $this->actingAs($user)->get(route('account.profile'))
            ->assertStatus(200);

        $this->actingAs($user)->get(route('account.profile', ['tab' => 'sessions']))
            ->assertStatus(200);
    }

    #[Test]
    public function can_update_profile()
    {
        $user = User::factory()->create([
            'password' => Hash::make('password'),
        ]);
        $user->generateTwoFASecret();

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
            ->set('confirmPassword', 'kXqz=k^zwu7d^;UrMPNF')
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

<?php

namespace Modules\AuthModule\Tests\Feature\Livewire\Auth;

use App\Facades\ModuleManager;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Livewire\Livewire;
use Modules\AuthModule\Livewire\Auth\Login;
use Modules\AuthModule\Models\User;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function renders_successfully()
    {
        $this->get(route('auth.login'))
            ->assertStatus(200);
    }

    #[Test]
    public function can_login(): void
    {
        $user = User::factory()->create([
            'password' => Hash::make('password'),
        ]);

        if (ModuleManager::getModule('DashboardModule')->isEnabled()) {
            Livewire::test(Login::class)
                ->set('username', $user->username)
                ->set('password', 'password')
                ->call('attemptLogin')
                ->assertRedirect(route('dashboard'));
        } else {
            Livewire::test(Login::class)
                ->set('username', $user->username)
                ->set('password', 'password')
                ->call('attemptLogin')
                ->assertRedirect('/');
        }
    }
}

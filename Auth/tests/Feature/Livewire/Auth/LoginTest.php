<?php

namespace Modules\Auth\Tests\Feature\Livewire\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Livewire\Livewire;
use Modules\Auth\Livewire\Auth\Login;
use Modules\Auth\Models\User;
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

        $this->session(['url.intended' => route('dashboard')]);

        Livewire::test(Login::class)
            ->set('username', $user->username)
            ->set('password', 'password')
            ->call('attemptLogin')
            ->assertRedirect(route('dashboard'));
    }
}

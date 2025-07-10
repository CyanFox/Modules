<?php

namespace Modules\Auth\Tests\Feature\Livewire\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Modules\Auth\Livewire\Auth\Register;
use PHPUnit\Framework\Attributes\Test;
use Modules\Auth\Tests\TestCase;

class RegisterTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function renders_successfully()
    {
        $this->get(route('auth.register'))
            ->assertStatus(200);
    }

    #[Test]
    public function can_register(): void
    {
        Livewire::test(Register::class)
            ->set('firstName', 'TestUser')
            ->set('lastName', 'TestUser')
            ->set('username', 'testuser')
            ->set('email', 'test@local.host')
            ->set('password', 'kXqz=k^zwu7d^;UrMPNF')
            ->set('passwordConfirmation', 'kXqz=k^zwu7d^;UrMPNF')
            ->call('register');

        $this->assertDatabaseHas('users', [
            'username' => 'testuser',
            'email' => 'test@local.host',
        ]);
    }
}

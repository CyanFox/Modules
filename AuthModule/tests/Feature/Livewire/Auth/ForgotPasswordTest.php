<?php

namespace Modules\AuthModule\Tests\Feature\Livewire\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Livewire\Livewire;
use Modules\AuthModule\Livewire\Auth\ForgotPassword;
use Modules\AuthModule\Models\User;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class ForgotPasswordTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function renders_successfully()
    {
        $this->get(route('auth.forgot-password', ''))
            ->assertStatus(200);

        User::factory()->create([
            'password_reset_token' => 'token',
            'password_reset_expiration' => now()->addHour(),
        ]);

        $this->get(route('auth.forgot-password', 'token'))
            ->assertStatus(200);
    }

    #[Test]
    public function can_reset_password(): void
    {
        $user = User::factory()->create([
            'password_reset_token' => 'token',
            'password_reset_expiration' => now()->addHour(),
        ]);

        Livewire::test(ForgotPassword::class)
            ->set('password', 'kXqz=k^zwu7d^;UrMPNF')
            ->set('passwordConfirmation', 'kXqz=k^zwu7d^;UrMPNF')
            ->set('resetToken', 'token')
            ->call('resetPassword');

        $this->assertTrue(Hash::check('kXqz=k^zwu7d^;UrMPNF', $user->fresh()->password));
    }
}

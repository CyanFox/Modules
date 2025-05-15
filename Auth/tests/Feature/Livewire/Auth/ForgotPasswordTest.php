<?php

namespace Modules\Auth\Tests\Feature\Livewire\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Livewire\Livewire;
use Modules\Auth\Livewire\Auth\ForgotPassword;
use Modules\Auth\Models\User;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class ForgotPasswordTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function renders_successfully()
    {
        $user = User::factory()->create();

        $this->get(route('auth.forgot-password'))
            ->assertStatus(200);

        $token = Str::random().'-'.$user->id;
        $user->update([
            'password_reset_token' => $token,
            'password_reset_expiration' => now()->addHour(),
        ]);

        $this->get(route('auth.forgot-password', $token))
            ->assertStatus(200);
    }

    #[Test]
    public function can_reset_password(): void
    {
        $user = User::factory()->create();
        $token = Str::random().'-'.$user->id;

        $user->update([
            'password_reset_token' => Hash::make($token),
            'password_reset_expiration' => now()->addHour(),
        ]);

        Livewire::test(ForgotPassword::class, ['passwordResetToken' => $token])
            ->set('password', 'kXqz=k^zwu7d^;UrMPNF')
            ->set('passwordConfirmation', 'kXqz=k^zwu7d^;UrMPNF')
            ->call('resetPassword');

        $this->assertTrue(Hash::check('kXqz=k^zwu7d^;UrMPNF', $user->fresh()->password));
    }
}

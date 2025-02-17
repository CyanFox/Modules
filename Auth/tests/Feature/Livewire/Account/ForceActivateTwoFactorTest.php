<?php

namespace Modules\Auth\Tests\Feature\Livewire\Account;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Livewire\Livewire;
use Modules\Auth\Livewire\Account\ForceActivateTwoFactor;
use Modules\Auth\Livewire\Account\ForceChangePassword;
use Modules\Auth\Livewire\Account\Profile;
use Modules\Auth\Livewire\Auth\Login;
use Modules\Auth\Models\User;
use PHPUnit\Framework\Attributes\Test;
use PragmaRX\Google2FA\Google2FA;
use Tests\TestCase;

class ForceActivateTwoFactorTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function renders_successfully()
    {
        $user = User::factory()->create([
            'force_activate_two_factor' => true,
        ]);
        $user->generateTwoFASecret();

        $this->actingAs($user)->get(route('account.force.activate-two-factor'))
            ->assertStatus(200);
    }

    #[Test]
    public function can_activate_two_factor()
    {
        $user = User::factory()->create([
            'password' => Hash::make('password'),
        ]);
        $user->generateTwoFASecret();

        $twoFactor = new Google2FA();
        $twoFactorCode = $twoFactor->getCurrentOtp(decrypt($user->two_factor_secret));

        Livewire::actingAs($user)
            ->test(ForceActivateTwoFactor::class)
            ->set('currentPassword', 'password')
            ->set('twoFactorCode', $twoFactorCode)
            ->call('activateTwoFA');

        $this->assertTrue($user->fresh()->two_factor_enabled);
    }
}

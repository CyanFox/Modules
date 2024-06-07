<?php

namespace Modules\AuthModule\Tests\Feature\Livewire\Account;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Livewire\Livewire;
use Modules\AuthModule\Facades\UserManager;
use Modules\AuthModule\Livewire\Account\ForceActivateTwoFactor;
use Modules\AuthModule\Models\User;
use PHPUnit\Framework\Attributes\Test;
use PragmaRX\Google2FA\Google2FA;
use Tests\TestCase;

class ForceActivateTwoFactorTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function renders_successfully()
    {
        $user = User::factory()->create();
        UserManager::getUser($user)->getTwoFactorManager()->generateTwoFactorSecret();

        $this->actingAs($user)->get(route('account.force-activate-two-factor'))
            ->assertStatus(200);
    }

    #[Test]
    public function can_activate_two_factor()
    {
        $user = User::factory()->create([
            'password' => Hash::make('password'),
        ]);
        UserManager::getUser($user)->getTwoFactorManager()->generateTwoFactorSecret();

        $twoFactor = new Google2FA();
        $twoFactorCode = $twoFactor->getCurrentOtp(decrypt($user->two_factor_secret));

        Livewire::actingAs($user)
            ->test(ForceActivateTwoFactor::class)
            ->set('password', 'password')
            ->set('twoFactorCode', $twoFactorCode)
            ->call('activateTwoFactor');

        $this->assertTrue($user->fresh()->two_factor_enabled === 1);
    }
}

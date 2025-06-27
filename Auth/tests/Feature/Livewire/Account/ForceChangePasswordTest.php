<?php

namespace Modules\Auth\Tests\Feature\Livewire\Account;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Livewire\Livewire;
use Modules\Auth\Livewire\Account\ForceChangePassword;
use Modules\Auth\Models\User;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class ForceChangePasswordTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function renders_successfully()
    {
        $user = User::factory()->create([
            'force_change_password' => true,
        ]);

        $this->actingAs($user)->get(route('account.force.change-password'))
            ->assertStatus(200);
    }

    #[Test]
    public function can_change_password()
    {
        $user = User::factory()->create([
            'password' => Hash::make('password'),
        ]);

        Livewire::actingAs($user)
            ->test(ForceChangePassword::class)
            ->set('currentPassword', 'password')
            ->set('newPassword', 'kXqz=k^zwu7d^;UrMPNF')
            ->set('confirmPassword', 'kXqz=k^zwu7d^;UrMPNF')
            ->call('changePassword');

        $this->assertTrue(Hash::check('kXqz=k^zwu7d^;UrMPNF', $user->fresh()->password));

    }
}

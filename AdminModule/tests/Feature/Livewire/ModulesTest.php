<?php

namespace Modules\AdminModule\Tests\Feature\Livewire;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\AdminModule\Tests\TestCase;
use Modules\AuthModule\Facades\UserManager;
use Modules\AuthModule\Models\User;
use PHPUnit\Framework\Attributes\Test;

class ModulesTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function renders_successfully()
    {
        $user = User::factory()->create();
        UserManager::getUser($user)->getTwoFactorManager()->generateTwoFactorSecret();
        $user->assignRole('Super Admin');

        $this->actingAs($user)->get(route('admin.modules'))
            ->assertStatus(200);
    }
}

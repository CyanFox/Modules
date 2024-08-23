<?php

namespace Feature\Livewire;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\AuthModule\Facades\UserManager;
use Modules\AuthModule\Models\User;
use PHPUnit\Framework\Attributes\Test;
use Modules\NotificationModule\tests\TestCase;

class NotificationsTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function renders_successfully()
    {
        $user = User::factory()->create();
        UserManager::getUser($user)->getTwoFactorManager()->generateTwoFactorSecret();
        $user->assignRole('Super Admin');

        $this->actingAs($user)->get(route('notifications'))
            ->assertStatus(200);
    }
}

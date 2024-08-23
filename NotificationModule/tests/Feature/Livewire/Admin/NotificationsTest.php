<?php

namespace Modules\NotificationModule\Tests\Feature\Livewire\Admin;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Livewire\Livewire;
use Modules\AuthModule\Facades\UserManager;
use Modules\AuthModule\Models\User;
use Modules\NotificationModule\Livewire\Admin\CreateNotification;
use Modules\NotificationModule\Livewire\Admin\UpdateNotification;
use Modules\NotificationModule\Models\Notification;
use Modules\NotificationModule\tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class NotificationsTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function renders_successfully()
    {
        $user = User::factory()->create();
        UserManager::getUser($user)->getTwoFactorManager()->generateTwoFactorSecret();
        $user->assignRole('Super Admin');

        $this->actingAs($user)->get(route('admin.notifications'))
            ->assertStatus(200);
    }

    #[Test]
    public function can_create_notification()
    {
        $user = User::factory()->create();
        UserManager::getUser($user)->getTwoFactorManager()->generateTwoFactorSecret();
        $user->assignRole('Super Admin');

        Livewire::actingAs($user)
            ->test(CreateNotification::class)
            ->set('title', 'Test')
            ->set('type', 'info')
            ->set('icon', 'bell')
            ->set('dismissible', true)
            ->set('location', 'notifications')
            ->set('message', 'Hello World')
            ->set('files', [UploadedFile::fake()->image('test.jpg')])
            ->call('createNotification');

        $this->assertDatabaseHas('notifications', [
            'title' => 'Test',
            'type' => 'info',
            'icon' => 'icon-bell',
            'dismissible' => true,
            'location' => 'notifications',
            'message' => 'Hello World',
        ]);

    }

    #[Test]
    public function can_update_notification()
    {
        $user = User::factory()->create();
        UserManager::getUser($user)->getTwoFactorManager()->generateTwoFactorSecret();
        $user->assignRole('Super Admin');

        $notificationToUpdate = Notification::create([
            'title' => 'Test',
            'type' => 'info',
            'icon' => 'bell',
            'dismissible' => true,
            'location' => 'notifications',
            'message' => 'Hello World',
        ]);

        Livewire::actingAs($user)
            ->test(UpdateNotification::class, ['notificationId' => $notificationToUpdate->id])
            ->set('title', 'Test Updated')
            ->set('type', 'success')
            ->set('icon', 'bell')
            ->set('dismissible', false)
            ->set('location', 'notifications')
            ->set('message', 'Hello World Updated')
            ->set('files', [UploadedFile::fake()->image('test.jpg')])
            ->call('updateNotification');


        $this->assertDatabaseHas('notifications', [
            'title' => 'Test Updated',
            'type' => 'success',
            'icon' => 'icon-bell',
            'dismissible' => false,
            'location' => 'notifications',
            'message' => 'Hello World Updated',
        ]);
    }
}

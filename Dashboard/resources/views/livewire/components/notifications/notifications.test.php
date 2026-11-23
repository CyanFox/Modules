<?php

use Illuminate\Notifications\Notification;
use Livewire\Livewire;

class DashboardTestNotification extends Notification
{
    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toDatabase($notifiable): array
    {
        return ['message' => 'Test notification'];
    }
}

it('renders successfully', function () {
    $this->actingAs(makeUser());

    Livewire::test('dashboard::components.notifications')
        ->assertStatus(200);
});

it('shows the unread notifications count', function () {
    $user = makeUser();
    $this->actingAs($user);

    $user->notify(new DashboardTestNotification);
    $user->notify(new DashboardTestNotification);

    Livewire::test('dashboard::components.notifications')
        ->assertSet('unreadNotificationsCount', 2);
});

it('removes a notification when it is marked as read', function () {
    $user = makeUser();
    $this->actingAs($user);

    $user->notify(new DashboardTestNotification);
    $notification = $user->notifications()->first();

    Livewire::test('dashboard::components.notifications')
        ->call('markAsRead', $notification->id)
        ->assertSet('unreadNotificationsCount', 0);

    expect($user->notifications()->count())->toBe(0);
});

it('removes all unread notifications when all are marked as read', function () {
    $user = makeUser();
    $this->actingAs($user);

    $user->notify(new DashboardTestNotification);
    $user->notify(new DashboardTestNotification);

    Livewire::test('dashboard::components.notifications')
        ->call('markAllAsRead')
        ->assertSet('unreadNotificationsCount', 0);

    expect($user->notifications()->count())->toBe(0);
});

it('lists the latest notifications', function () {
    $user = makeUser();
    $this->actingAs($user);

    $user->notify(new DashboardTestNotification);
    $user->notify(new DashboardTestNotification);

    $component = Livewire::test('dashboard::components.notifications');

    expect($component->get('notifications'))->toHaveCount(2);
});

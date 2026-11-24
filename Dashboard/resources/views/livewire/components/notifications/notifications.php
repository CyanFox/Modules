<?php

use Livewire\Component;

new class extends Component
{
    public $unreadNotificationsCount = 0;

    public $notifications = [];

    public function markAsRead(string $notificationId): void
    {
        auth()->user()->notifications()
            ->findOrFail($notificationId)
            ->delete();

        $this->unreadNotificationsCount = $this->getUnreadNotificationsCount();
    }

    public function markAllAsRead(): void
    {
        auth()->user()->unreadNotifications()->delete();

        $this->unreadNotificationsCount = $this->getUnreadNotificationsCount();
    }

    public function loadNewNotifications(): void
    {
        $this->notifications = auth()->user()->notifications()->take(10)->get();
        $this->unreadNotificationsCount = $this->getUnreadNotificationsCount();
    }

    public function getUnreadNotificationsCount(): int
    {
        return auth()->user()->unreadNotifications()->count();
    }

    protected function getListeners(): array
    {
        return [
            'echo-notification:Modules.Core.Models.User.'.auth()->id() => 'loadNewNotifications',
        ];
    }

    public function mount()
    {
        $this->loadNewNotifications();
    }

    public function render()
    {
        return $this->view();
    }
};

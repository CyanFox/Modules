<?php

namespace Modules\NotificationModule\Livewire;

use App\Services\LWComponent;
use Illuminate\Support\Facades\Storage;
use Modules\NotificationModule\Models\DismissedNotification;
use Modules\NotificationModule\Models\Notification;

class Notifications extends LWComponent
{
    public $currentLocation = 'notifications';
    public $notifications;

    public function dismissNotification($notificationId)
    {
        $userId = auth()->id();

        $notification = Notification::query()
            ->leftJoin('dismissed_notifications', function ($join) use ($userId) {
                $join->on('notifications.id', '=', 'dismissed_notifications.notification_id')
                    ->where('dismissed_notifications.user_id', '=', $userId);
            })
            ->whereNull('dismissed_notifications.notification_id')
            ->where('notifications.id', $notificationId)
            ->first(['notifications.*']);

        if ($notification) {
            $notification->dismissedNotification()->create([
                'user_id' => $userId,
                'notification_id' => $notificationId,
            ]);
        }

        $this->redirect(url()->previous(), navigate: true);
    }

    public function downloadFile($notificationId, $fileName)
    {
        if (str_contains($notificationId, '../') || str_contains($fileName, '../')) {
            return null;
        }
        return Storage::download('public/notifications/' . $notificationId . '/' . $fileName);
    }

    public function mount()
    {
        $userId = auth()->id();

        $this->notifications = Notification::query()
            ->leftJoin('dismissed_notifications', function ($join) use ($userId) {
                $join->on('notifications.id', '=', 'dismissed_notifications.notification_id')
                    ->where('dismissed_notifications.user_id', '=', $userId);
            })
            ->whereNull('dismissed_notifications.notification_id')
            ->orderBy('notifications.created_at', 'desc')
            ->get(['notifications.*']);

        foreach ($this->notifications as $notification) {
            $notification->dismissed = DismissedNotification::where('user_id', auth()->id())
                ->where('notification_id', $notification->id)
                ->exists();

            $types = [
                'info' => ['badge' => 'primary', 'border' => 'border-blue-500'],
                'warning' => ['badge' => 'yellow', 'border' => 'border-yellow-500'],
                'success' => ['badge' => 'green', 'border' => 'border-green-500'],
                'update' => ['badge' => 'cyan', 'border' => 'border-cyan-500'],
                'danger' => ['badge' => 'red', 'border' => 'border-red-500'],
            ];

            $type = $types[$notification->type] ?? $types['info'];

            $notification->badge = $type['badge'];
            $notification->border = $type['border'];

            $notification->files = Storage::files('public/notifications/' . $notification->id);
        }
    }

    public function render()
    {
        return $this->renderView('notificationmodule::livewire.notifications', __('notificationmodule::notifications.tab_title'), 'dashboardmodule::components.layouts.app');
    }
}

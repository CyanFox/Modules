<?php

namespace Modules\NotificationModule\app\Livewire\Components\Modals\Admin;

use Exception;
use Livewire\Attributes\On;
use LivewireUI\Modal\ModalComponent;
use Modules\NotificationModule\app\Models\Notification;
use Storage;

class DeleteNotification extends ModalComponent
{
    public $notificationId;

    public function deleteNotification()
    {
        try {
            $notification = Notification::findOrFail($this->notificationId);
            $notification->delete();

            Storage::disk('public')->deleteDirectory('notifications/'.$this->notificationId);
        } catch (Exception $e) {
            \Filament\Notifications\Notification::make()
                ->title(__('messages.notifications.something_went_wrong'))
                ->danger()
                ->send();

            $this->dispatch('logger', ['type' => 'error', 'message' => $e->getMessage()]);

            return;
        }

        \Filament\Notifications\Notification::make()
            ->title(__('notificationmodule::notifications.modals.delete_notification.notifications.notification_deleted'))
            ->success()
            ->send();

        $this->closeModal();
        $this->dispatch('refresh');
    }

    #[On('refresh')]
    public function render()
    {
        return view('notificationmodule::livewire.components.modals.admin.delete-notification');
    }
}

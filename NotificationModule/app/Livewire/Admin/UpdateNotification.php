<?php

namespace Modules\NotificationModule\Livewire\Admin;

use App\Services\LWComponent;
use Exception;
use Filament\Notifications\Notification;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Livewire\WithFileUploads;
use SplFileInfo;

class UpdateNotification extends LWComponent
{
    use WithFileUploads;

    public $notificationId;

    public $title;

    public $type;

    public $dismissible;

    public $location;

    public $icon = 'bell';

    public $message;

    public $permissions = [];
    public $files = [];
    public $existingFiles = [];

    public function updateNotification()
    {
        $this->validate([
            'title' => 'required',
            'type' => 'required',
            'icon' => 'required',
            'dismissible' => 'required',
            'location' => 'required',
            'message' => 'nullable',
            'files.*' => 'nullable|file',
        ]);

        try {
            $notification = \Modules\NotificationModule\Models\Notification::findOrfail($this->notificationId);
            $notification->update([
                'title' => $this->title,
                'type' => $this->type,
                'icon' => 'icon-' . $this->icon,
                'dismissible' => $this->dismissible,
                'location' => $this->location,
                'message' => $this->message,
                'permissions' => json_encode($this->permissions),
            ]);

            if ($this->files) {
                foreach ($this->files as $file) {
                    $file->storeAs('public/notifications/' . $notification->id, $file->getClientOriginalName());
                }
            }
        } catch (Exception $e) {
            Notification::make()
                ->title(__('messages.notifications.something_went_wrong'))
                ->danger()
                ->send();

            $this->log($e->getMessage(), 'error');

            return;
        }

        Notification::make()
            ->title(__('notificationmodule::notifications.update_notification.notifications.notification_updated'))
            ->success()
            ->send();

        $this->redirect(route('admin.notifications'), navigate: true);
    }

    public function deleteFile(array $content)
    {
        if (!$this->files) {
            return;
        }

        $files = Arr::wrap($this->files);

        $file = collect($files)->filter(fn(UploadedFile $item) => $item->getFilename() === $content['temporary_name'])->first();

        rescue(fn() => $file->delete(), report: false);

        $collect = collect($files)->filter(fn(UploadedFile $item) => $item->getFilename() !== $content['temporary_name']);

        $this->files = is_array($this->files) ? $collect->toArray() : $collect->first();
    }

    public function deleteExistingFile($file)
    {
        Storage::disk('public')->delete('notifications/' . $this->notificationId . '/' . $file);
        $this->existingFiles = Storage::files('public/notifications/' . $this->notificationId);
    }

    public function mount()
    {
        $notification = \Modules\NotificationModule\Models\Notification::findOrfail($this->notificationId);
        $this->title = $notification->title;
        $this->type = $notification->type;
        $this->icon = str_replace('icon-', '', $notification->icon);
        $this->dismissible = $notification->dismissible;
        $this->location = $notification->location;
        $this->message = $notification->message;
        $this->permissions = json_decode($notification->permissions, true);
        $this->existingFiles = Storage::files('public/notifications/' . $notification->id);
    }

    public function render()
    {
        return $this->renderView('notificationmodule::livewire.admin.update-notification', __('notificationmodule::notifications.update_notification.tab_title'), 'adminmodule::components.layouts.app');
    }
}

<?php

namespace Modules\NotificationModule\Livewire\Admin;

use App\Services\LWComponent;
use Exception;
use Filament\Notifications\Notification;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Arr;
use Livewire\WithFileUploads;

class CreateNotification extends LWComponent
{
    use WithFileUploads;

    public $title;
    public $type;
    public $dismissible;
    public $location;
    public $icon = 'bell';
    public $message;
    public $files = [];
    public $permissions = [];

    public function createNotification()
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
            $notification = \Modules\NotificationModule\Models\Notification::create([
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
            ->title(__('notificationmodule::notifications.create_notification.notifications.notification_created'))
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

    public function render()
    {
        return $this->renderView('notificationmodule::livewire.admin.create-notification', __('notificationmodule::notifications.create_notification.tab_title'), 'adminmodule::components.layouts.app');
    }
}

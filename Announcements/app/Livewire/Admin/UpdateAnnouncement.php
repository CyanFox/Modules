<?php

namespace Modules\Announcements\Livewire\Admin;

use App\Livewire\CFComponent;
use App\Traits\WithCustomLivewireException;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;
use Modules\Announcements\Models\Announcement;
use Modules\Auth\Traits\WithConfirmation;

class UpdateAnnouncement extends CFComponent
{
    use WithCustomLivewireException, WithFileUploads, WithConfirmation;

    public $announcementId;
    public $announcement;
    public $title;
    public $icon = 'megaphone';
    public $color = 'info';
    public $description;
    public $dismissible = false;
    public $disabled = false;
    public $uploadedFiles = [];
    public $files = [];
    public $groups = [];
    public $permissions = [];
    public $users = [];

    public function updateAnnouncement()
    {
        $this->validate([
            'title' => 'required',
            'icon' => 'required',
            'color' => 'required|in:info,success,warning,danger',
            'dismissible' => 'nullable|boolean',
            'disabled' => 'nullable|boolean',
            'files' => 'array',
            'files.*' => 'file|max:12000', // 12MB
            'groups' => 'array|exists:roles,id',
            'permissions' => 'array|exists:permissions,id',
            'users' => 'array|exists:users,id',
        ]);

        $this->announcement->update([
            'title' => $this->title,
            'icon' => $this->icon,
            'color' => $this->color,
            'description' => $this->description,
            'dismissible' => $this->dismissible,
            'disabled' => $this->disabled,
        ]);

        foreach ($this->files as $file) {
            $file->storeAs('announcements/' . $this->announcementId, $file->getClientOriginalName(), 'local');
        }

        $this->announcement->access()->where('announcement_id', $this->announcementId)->delete();
        foreach ($this->groups as $group) {
            $this->announcement->access()->create([
                'group_id' => $group,
                'announcement_id' => $this->announcement->id,
            ]);
        }

        foreach ($this->permissions as $permission) {
            $this->announcement->access()->create([
                'permission_id' => $permission,
                'announcement_id' => $this->announcement->id,
            ]);
        }

        foreach ($this->users as $user) {
            $this->announcement->access()->create([
                'user_id' => $user,
                'announcement_id' => $this->announcement->id,
            ]);
        }

        Notification::make()
            ->title(__('announcements::announcements.update_announcement.notifications.announcement_updated'))
            ->success()
            ->send();

        return redirect()->route('admin.announcements.update', ['announcementId' => $this->announcementId]);
    }

    public function deleteFile($file, $confirmed = true)
    {
        if ($confirmed) {
            if (preg_match('/[\/:*?"<>|]/', $file)) {
                return;
            }

            if (Storage::disk('local')->exists('announcements/' . $this->announcementId . '/' . $file)) {
                Storage::disk('local')->delete('announcements/' . $this->announcementId . '/' . $file);

                Notification::make()
                    ->title(__('announcements::announcements.update_announcement.notifications.file_deleted'))
                    ->success()
                    ->send();
            }

            $this->redirect(route('admin.announcements.update', ['announcementId' => $this->announcementId]), true);

            return;
        }

        $this->dialog()
            ->question(__('announcements::announcements.update_announcement.delete_file.title'),
                __('announcements::announcements.update_announcement.delete_file.description'))
            ->icon('icon-triangle-alert')
            ->confirm(__('announcements::announcements.update_announcement.delete_file.buttons.delete_file'), 'danger')
            ->method('deleteFile', $file)
            ->send();
    }

    public function mount()
    {
        $this->announcement = Announcement::find($this->announcementId);
        $this->title = $this->announcement->title;
        $this->icon = $this->announcement->icon;
        $this->color = $this->announcement->color;
        $this->description = $this->announcement->description;
        $this->dismissible = (bool) $this->announcement->dismissible;
        $this->disabled = (bool) $this->announcement->disabled;

        $this->groups = $this->announcement->access->where('group_id', '!=', null)->pluck('group_id')->toArray();
        $this->permissions = $this->announcement->access->where('permission_id', '!=', null)->pluck('permission_id')->toArray();
        $this->users = $this->announcement->access->where('user_id', '!=', null)->pluck('user_id')->toArray();

        $this->uploadedFiles = collect(Storage::disk('local')->files('announcements/' . $this->announcement->id))
            ->map(function ($filePath) {
                return [
                    'name' => basename($filePath),
                    'size' => formatFileSize(Storage::disk('local')->size($filePath)),
                    'path' => $filePath
                ];
            })->toArray();
    }

    public function render()
    {
        return $this->renderView('announcements::livewire.admin.update-announcement', __('announcements::announcements.update_announcement.tab_title'), 'admin::components.layouts.app');
    }
}

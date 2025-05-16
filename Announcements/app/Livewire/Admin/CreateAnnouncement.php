<?php

namespace Modules\Announcements\Livewire\Admin;

use App\Livewire\CFComponent;
use App\Traits\WithCustomLivewireException;
use Filament\Notifications\Notification;
use Livewire\Component;
use Livewire\WithFileUploads;
use Modules\Announcements\Models\Announcement;

class CreateAnnouncement extends CFComponent
{
    use WithCustomLivewireException, WithFileUploads;

    public $title;
    public $icon = 'megaphone';
    public $color = 'info';
    public $description;
    public $dismissible = false;
    public $disabled = false;
    public $files = [];
    public $groups = [];
    public $permissions = [];
    public $users = [];

    public function createAnnouncement()
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

        $announcement = Announcement::create([
            'title' => $this->title,
            'icon' => $this->icon,
            'color' => $this->color,
            'description' => $this->description,
            'dismissible' => $this->dismissible,
            'disabled' => $this->disabled,
        ]);

        foreach ($this->files as $file) {
            $file->storeAs('announcements/' . $announcement->id, $file->getClientOriginalName(), 'local');
        }

        foreach ($this->groups as $group) {
            $announcement->access()->create([
                'group_id' => $group,
                'announcement_id' => $announcement->id,
            ]);
        }

        foreach ($this->permissions as $permission) {
            $announcement->access()->create([
                'permission_id' => $permission,
                'announcement_id' => $announcement->id,
            ]);
        }

        foreach ($this->users as $user) {
            $announcement->access()->create([
                'user_id' => $user,
                'announcement_id' => $announcement->id,
            ]);
        }

        Notification::make()
            ->title(__('announcements::announcements.create_announcement.notifications.announcement_created'))
            ->success()
            ->send();

        $this->redirect(route('admin.announcements'), true);
    }

    public function render()
    {
        return $this->renderView('announcements::livewire.admin.create-announcement', __('announcements::announcements.create_announcement.tab_title'), 'admin::components.layouts.app');
    }
}

<?php

namespace Modules\Admin\Livewire\Groups;

use App\Livewire\CFComponent;
use App\Traits\WithCustomLivewireException;
use Filament\Notifications\Notification;
use Spatie\Permission\Exceptions\RoleDoesNotExist;
use Spatie\Permission\Models\Role;

class UpdateGroup extends CFComponent
{
    use WithCustomLivewireException;

    public $groupId;

    public $group;

    public $name;

    public $guardName;

    public $permissions = [];

    public function updateGroup()
    {
        $this->validate([
            'name' => 'required|string|unique:roles,name,'.$this->groupId,
            'guardName' => 'required|string',
            'permissions' => 'nullable|array',
        ]);

        $this->group->update([
            'name' => $this->name,
            'guard_name' => $this->guardName,
        ]);

        if ($this->permissions) {
            $this->group->syncPermissions($this->permissions);
        }

        Notification::make()
            ->title(__('admin::groups.update_group.notifications.group_updated'))
            ->success()
            ->send();

        $this->redirect(route('admin.groups.update', ['groupId' => $this->groupId]), true);
    }

    public function mount()
    {
        try {
            $this->group = Role::findById($this->groupId);
        } catch (RoleDoesNotExist) {
            abort(404);
        }

        if ($this->group->name === 'Super Admin') {
            abort(404);
        }

        $this->name = $this->group->name;
        $this->guardName = $this->group->guard_name;
        $this->permissions = $this->group->permissions->pluck('name')->toArray();
    }

    public function render()
    {
        return $this->renderView('admin::livewire.groups.update-group', __('admin::groups.update_group.tab_title'), 'admin::components.layouts.app');
    }
}

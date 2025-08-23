<?php

namespace Modules\Admin\Livewire\Groups;

use App\Livewire\CFComponent;
use App\Traits\WithCustomLivewireException;
use Masmerise\Toaster\Toaster;
use Modules\Auth\Actions\Groups\CreateGroupAction;

class CreateGroup extends CFComponent
{
    use WithCustomLivewireException;

    public $name;

    public $guardName;

    public $permissions = [];

    public function createGroup()
    {
        $this->validate([
            'name' => 'required|string|unique:roles,name',
            'guardName' => 'required|string',
            'permissions' => 'nullable|array',
        ]);

        $group = CreateGroupAction::run([
            'name' => $this->name,
            'guard_name' => $this->guardName,
        ]);

        if ($this->permissions) {
            $group->syncPermissions($this->permissions);
        }

        Toaster::success(__('admin::groups.create_group.notifications.group_created'));

        $this->redirect(route('admin.groups'), true);
    }

    public function mount()
    {
        $this->guardName = 'web';
    }

    public function render()
    {
        return $this->renderView('admin::livewire.groups.create-group', __('admin::groups.create_group.tab_title'), 'admin::components.layouts.app');
    }
}

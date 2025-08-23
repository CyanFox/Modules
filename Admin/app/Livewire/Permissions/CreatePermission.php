<?php

namespace Modules\Admin\Livewire\Permissions;

use App\Livewire\CFComponent;
use App\Traits\WithCustomLivewireException;
use Masmerise\Toaster\Toaster;
use Modules\Auth\Actions\Permissions\CreatePermissionAction;

class CreatePermission extends CFComponent
{
    use WithCustomLivewireException;

    public $name;

    public $guardName;

    public function createPermission()
    {
        $this->validate([
            'name' => 'required|string|unique:permissions,name',
            'guardName' => 'required|string',
        ]);

        CreatePermissionAction::run([
            'name' => $this->name,
            'guard_name' => $this->guardName,
        ]);

        Toaster::success(__('admin::permissions.create_permission.notifications.permission_created'));

        $this->redirect(route('admin.permissions'), true);
    }

    public function mount()
    {
        $this->guardName = 'web';
    }

    public function render()
    {
        return $this->renderView('admin::livewire.permissions.create-permission', __('admin::permissions.create_permission.tab_title'), 'admin::components.layouts.app');
    }
}

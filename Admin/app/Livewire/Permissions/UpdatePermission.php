<?php

namespace Modules\Admin\Livewire\Permissions;

use App\Livewire\CFComponent;
use App\Traits\WithCustomLivewireException;
use Filament\Notifications\Notification;
use Spatie\Permission\Exceptions\PermissionDoesNotExist;
use Spatie\Permission\Models\Permission;

class UpdatePermission extends CFComponent
{
    use WithCustomLivewireException;

    public $permissionId;
    public $permission;
    public $name;
    public $guardName;

    public function updatePermission()
    {
        $this->validate([
            'name' => 'required|string|unique:permissions,name,' . $this->permissionId,
            'guardName' => 'required|string',
        ]);

        $this->permission->update([
            'name' => $this->name,
            'guard_name' => $this->guardName,
        ]);

        Notification::make()
            ->title(__('admin::permissions.update_permission.notifications.permission_updated'))
            ->success()
            ->send();

        $this->redirect(route('admin.permissions'), true);
    }

    public function mount()
    {
        try {
            $this->permission = Permission::findById($this->permissionId);
        }catch (PermissionDoesNotExist) {
            abort(404);
        }

        $this->name = $this->permission->name;
        $this->guardName = $this->permission->guard_name;
    }

    public function render()
    {
        return $this->renderView('admin::livewire.permissions.update-permission', __('admin::permissions.update_permission.tab_title'), 'admin::components.layouts.app');
    }
}

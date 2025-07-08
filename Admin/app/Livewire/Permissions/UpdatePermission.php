<?php

namespace Modules\Admin\Livewire\Permissions;

use App\Livewire\CFComponent;
use App\Traits\WithCustomLivewireException;
use Filament\Notifications\Notification;
use Modules\Auth\Actions\Permissions\UpdatePermissionAction;
use Modules\Auth\Models\Permission;
use Spatie\Permission\Exceptions\PermissionDoesNotExist;

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
            'name' => 'required|string|unique:permissions,name,'.$this->permissionId,
            'guardName' => 'required|string',
        ]);

        UpdatePermissionAction::run($this->permission, [
            'name' => $this->name,
            'guard_name' => $this->guardName,
        ]);

        Notification::make()
            ->title(__('admin::permissions.update_permission.notifications.permission_updated'))
            ->success()
            ->send();

        $this->redirect(route('admin.permissions.update', ['permissionId' => $this->permissionId]), true);
    }

    public function mount()
    {
        try {
            $this->permission = Permission::findById($this->permissionId);
        } catch (PermissionDoesNotExist) {
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

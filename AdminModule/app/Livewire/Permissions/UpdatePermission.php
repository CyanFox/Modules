<?php

namespace Modules\AdminModule\Livewire\Permissions;

use App\Services\LWComponent;
use Exception;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Permission;

class UpdatePermission extends LWComponent
{
    public $permissionId;

    public $name;

    public $guardName;

    public $module;

    public function updatePermission()
    {
        if (Auth::user()->cannot('adminmodule.permissions.update')) {
            return;
        }

        $this->validate([
            'name' => 'required|unique:permissions,name,' . $this->permissionId,
            'guardName' => 'required',
            'module' => 'required',
        ]);

        try {
            $permission = Permission::findOrFail($this->permissionId);

            $permission->update([
                'name' => $this->name,
                'guard_name' => $this->guardName,
                'module' => $this->module,
            ]);
        } catch (Exception $e) {
            Notification::make()
                ->title(__('messages.notifications.something_went_wrong'))
                ->danger()
                ->send();

            $this->log($e->getMessage(), 'error');

            return;
        }

        Notification::make()
            ->title(__('adminmodule::permissions.update_permission.notifications.permission_updated'))
            ->success()
            ->send();

        $this->redirect(route('admin.permissions'), navigate: true);
    }

    public function mount()
    {
        $permission = Permission::findOrFail($this->permissionId);

        $this->name = $permission->name;
        $this->guardName = $permission->guard_name;
        $this->module = $permission->module;
    }

    public function render()
    {
        return $this->renderView('adminmodule::livewire.permissions.update-permission', __('adminmodule::permissions.create_permission.tab_title'), 'adminmodule::components.layouts.app');
    }
}

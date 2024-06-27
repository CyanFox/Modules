<?php

namespace Modules\AdminModule\Livewire;

use App\Services\LWComponent;
use Exception;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;
use Spatie\Permission\Models\Permission;

class Permissions extends LWComponent
{
    public $name;

    public $guardName = 'web';

    public $module;

    public $permissionId;

    public function createPermission()
    {
        if (Auth::user()->cannot('adminmodule.permissions.create')) {
            return;
        }

        $this->validate([
            'name' => 'required|unique:roles,name',
            'guardName' => 'required',
            'module' => 'required',
        ]);

        try {
            Permission::create([
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
            ->title(__('adminmodule::permissions.create_permission.notifications.permission_created'))
            ->success()
            ->send();

        $this->redirect(route('admin.permissions'), navigate: true);
    }

    #[On('updatePermissionParams')]
    public function updatePermissionParams($permissionId)
    {
        try {

            $permission = Permission::findOrFail($permissionId);

            $this->name = $permission->name;
            $this->guardName = $permission->guard_name;
            $this->module = $permission->module;
            $this->permissionId = $permission->id;
        } catch (Exception $e) {
            Notification::make()
                ->title(__('messages.notifications.something_went_wrong'))
                ->danger()
                ->send();

            $this->log($e->getMessage(), 'error');

            return;
        }
    }

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

    #[On('clearForm')]
    public function clearForm()
    {
        $this->name = null;
        $this->guardName = 'web';
        $this->module = null;
    }

    public function render()
    {
        return $this->renderView('adminmodule::livewire.permissions', __('adminmodule::permissions.tab_title'), 'adminmodule::components.layouts.app');
    }
}

<?php

namespace Modules\AdminModule\Livewire\Groups;

use App\Services\LWComponent;
use Exception;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class UpdateGroup extends LWComponent
{
    public $groupId;

    public $name;

    public $guardName;

    public $module;

    public $permissions = [];

    public $permissionList = [];

    public function updateGroup()
    {
        if (Auth::user()->cannot('adminmodule.groups.update')) {
            return;
        }

        $this->validate([
            'name' => 'required|unique:roles,name,' . $this->groupId,
            'guardName' => 'required',
            'module' => 'required',
            'permissions' => 'nullable|array',
        ]);

        try {
            $group = Role::findOrFail($this->groupId);
            if ($group->name === 'Super Admin') {
                return;
            }

            $group->update([
                'name' => $this->name,
                'guard_name' => $this->guardName,
                'module' => $this->module,
            ]);

            if ($this->permissions) {
                $group->syncPermissions($this->permissions);
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
            ->title(__('adminmodule::groups.update_group.notifications.group_updated'))
            ->success()
            ->send();

        $this->redirect(route('admin.groups'), navigate: true);
    }


    public function mount()
    {
        $this->permissionList = Permission::all()->map(function ($permission) {
            return [
                'label' => $permission->name,
                'value' => $permission->name,
            ];
        })->toArray();

        $group = Role::findOrFail($this->groupId);
        $this->name = $group->name;
        $this->guardName = $group->guard_name;
        $this->module = $group->module;
        $this->permissions = $group->permissions->pluck('name')->toArray();
    }

    public function render()
    {
        return $this->renderView('adminmodule::livewire.groups.update-group', __('adminmodule::groups.update_group.tab_title'), 'adminmodule::components.layouts.app');
    }
}

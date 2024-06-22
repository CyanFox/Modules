<?php

namespace Modules\AdminModule\Livewire;

use App\Services\LWComponent;
use Exception;
use Filament\Notifications\Notification;
use Livewire\Attributes\On;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class Groups extends LWComponent
{
    public $name;

    public $guardName = 'web';

    public $module;

    public $permissions = [];

    public $permissionList = [];

    public $groupId;

    public function createGroup()
    {
        $this->validate([
            'name' => 'required|unique:roles,name',
            'guardName' => 'required',
            'module' => 'required',
            'permissions' => 'nullable|array',
        ]);

        try {
            $group = Role::create([
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
            ->title(__('adminmodule::groups.create_group.notifications.group_created'))
            ->success()
            ->send();

        $this->redirect(route('admin.groups'), navigate: true);
    }

    #[On('updateGroupParams')]
    public function updateGroupParams($groupId)
    {
        try {

            $group = Role::findOrFail($groupId);

            $this->groupId = $group->id;

            $this->name = $group->name;
            $this->guardName = $group->guard_name;
            $this->module = $group->module;
            $this->permissions = $group->permissions->pluck('name')->toArray();
        } catch (Exception $e) {
            Notification::make()
                ->title(__('messages.notifications.something_went_wrong'))
                ->danger()
                ->send();

            $this->log($e->getMessage(), 'error');

            return;
        }
    }

    public function updateGroup()
    {
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

    #[On('clearForm')]
    public function clearForm()
    {
        $this->name = null;
        $this->guardName = 'web';
        $this->module = null;
        $this->permissions = [];
    }

    public function mount()
    {
        $this->permissionList = Permission::all()->map(function ($permission) {
            return [
                'label' => $permission->name,
                'value' => $permission->name,
            ];
        })->toArray();
    }

    public function render()
    {
        return $this->renderView('adminmodule::livewire.groups', __('adminmodule::groups.tab_title'), 'adminmodule::components.layouts.app');
    }
}

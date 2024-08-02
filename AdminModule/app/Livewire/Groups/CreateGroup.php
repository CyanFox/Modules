<?php

namespace Modules\AdminModule\Livewire\Groups;

use App\Services\LWComponent;
use Exception;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class CreateGroup extends LWComponent
{
    public $name;

    public $guardName = 'web';

    public $module;

    public $permissions = [];

    public $permissionList = [];

    public function createGroup()
    {
        if (Auth::user()->cannot('adminmodule.groups.create')) {
            return;
        }

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
        return $this->renderView('adminmodule::livewire.groups.create-group', __('adminmodule::groups.create_group.tab_title'), 'adminmodule::components.layouts.app');
    }
}

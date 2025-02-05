<?php

namespace Modules\Admin\Livewire\Permissions;

use App\Livewire\CFComponent;
use App\Traits\WithCustomLivewireException;
use Filament\Notifications\Notification;
use Spatie\Permission\Models\Permission;

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

        Permission::create([
            'name' => $this->name,
            'guard_name' => $this->guardName,
        ]);

        Notification::make()
            ->title(__('admin::permissions.create_permission.notifications.permission_created'))
            ->success()
            ->send();

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

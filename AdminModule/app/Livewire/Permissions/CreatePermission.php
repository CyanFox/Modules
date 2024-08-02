<?php

namespace Modules\AdminModule\Livewire\Permissions;

use App\Services\LWComponent;
use Exception;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Spatie\Permission\Models\Permission;

class CreatePermission extends LWComponent
{
    public $name;

    public $guardName = 'web';

    public $module;

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

    public function render()
    {
        return $this->renderView('adminmodule::livewire.permissions.create-permission', __('adminmodule::permissions.create_permission.tab_title'), 'adminmodule::components.layouts.app');
    }
}

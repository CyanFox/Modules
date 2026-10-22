<?php

use Illuminate\Contracts\View\View;
use Livewire\Component;
use Modules\Core\Models\Permission;

new class extends Component {
    public $permissionId;
    public $permission;
    public $name;
    public $guardName = 'web';

    public function updatePermission()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'guardName' => 'required|string|max:255',
        ]);

        $this->permission->update([
            'name' => $this->name,
            'guard_name' => $this->guardName,
        ]);

        Toaster::success(__('admin::permissions.update.notifications.updated'));

        $this->redirect(route('admin.permissions'), true);
    }

    public function mount()
    {
        $this->permission = Permission::find($this->permissionId);
        if (!$this->permission) {
            abort(404);
        }

        $this->name = $this->permission->name;
        $this->guardName = $this->permission->guard_name;
    }

    public function render(): View
    {
        return $this->view()
            ->layout('admin::layouts.app', ['breadcrumbs' => [['label' => __('admin::admin.admin'), 'url' => route('admin.dashboard')],
                ['label' => __('admin::permissions.tab_title'), 'url' => route('admin.permissions')], ['label' => __('admin::permissions.update.tab_title'), 'last' => true]]])
            ->title(__('admin::permissions.update.tab_title'));
    }
};

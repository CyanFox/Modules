<?php

use Illuminate\Contracts\View\View;
use Livewire\Component;
use Modules\Core\Models\Role;

new class extends Component {
    public $roleId;
    public $role;
    public $name;
    public $guardName = 'web';
    public $permissions = [];

    public function updateRole()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'guardName' => 'required|string|max:255',
            'permissions' => 'nullable|array',
        ]);

        $this->role->update([
            'name' => $this->name,
            'guard_name' => $this->guardName,
        ]);

        $this->role->syncPermissions($this->permissions);

        Toaster::success(__('admin::roles.update.notifications.updated'));

        $this->redirect(route('admin.roles'), true);
    }

    public function mount()
    {
        $this->role = Role::find($this->roleId);
        if (!$this->role) {
            abort(404);
        }

        $this->name = $this->role->name;
        $this->guardName = $this->role->guard_name;
        $this->permissions = $this->role->permissions->pluck('name')->toArray();
    }

    public function render(): View
    {
        return $this->view()
            ->layout('admin::layouts.app', ['breadcrumbs' => [['label' => __('admin::admin.admin'), 'url' => route('admin.dashboard')],
                ['label' => __('admin::roles.tab_title'), 'url' => route('admin.roles')], ['label' => __('admin::roles.update.tab_title'), 'last' => true]]])
            ->title(__('admin::roles.update.tab_title'));
    }
};

<?php

use Illuminate\Contracts\View\View;
use Livewire\Component;
use Modules\Core\Models\Role;

new class extends Component {
    public $name;
    public $guardName = 'web';
    public $permissions = [];

    public function createRole()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'guardName' => 'required|string|max:255',
            'permissions' => 'nullable|array',
        ]);

        $role = Role::create([
            'name' => $this->name,
            'guard_name' => $this->guardName,
        ]);

        $role->syncPermissions($this->permissions);

        Toaster::success(__('admin::roles.create.notifications.created'));

        $this->redirect(route('admin.roles'), true);
    }

    public function render(): View
    {
        return $this->view()
            ->layout('admin::layouts.app', ['breadcrumbs' => [['label' => __('admin::admin.admin'), 'url' => route('admin.dashboard')],
                ['label' => __('admin::roles.tab_title'), 'url' => route('admin.roles')], ['label' => __('admin::roles.create.tab_title'), 'last' => true]]])
            ->title(__('admin::roles.create.tab_title'));
    }
};

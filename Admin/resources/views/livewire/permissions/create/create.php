<?php

use Illuminate\Contracts\View\View;
use Livewire\Component;
use Modules\Core\Models\Permission;

new class extends Component {
    public $name;
    public $guardName = 'web';

    public function createPermission()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'guardName' => 'required|string|max:255',
        ]);

        Permission::create([
            'name' => $this->name,
            'guard_name' => $this->guardName,
        ]);

        Toaster::success(__('admin::permissions.create.notifications.created'));

        $this->redirect(route('admin.permissions'), true);
    }

    public function render(): View
    {
        return $this->view()
            ->layout('admin::layouts.app', ['breadcrumbs' => [['label' => __('admin::admin.admin'), 'url' => route('admin.dashboard')],
                ['label' => __('admin::permissions.tab_title'), 'url' => route('admin.permissions')], ['label' => __('admin::permissions.create.tab_title'), 'last' => true]]])
            ->title(__('admin::permissions.create.tab_title'));
    }
};

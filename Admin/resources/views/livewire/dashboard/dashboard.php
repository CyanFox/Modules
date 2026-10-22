<?php

use Illuminate\Contracts\View\View;
use Livewire\Component;
use Modules\Core\Models\Permission;
use Modules\Core\Models\Role;
use Nwidart\Modules\Facades\Module;

new class extends Component {
    public array $stats = [];

    public function mount(): void
    {
        $this->stats = [
            'users' => userModel()->count(),
            'roles' => Role::count(),
            'permissions' => Permission::count(),
            'modules' => count(Module::allEnabled()),
        ];
    }

    public function render(): View
    {
        return $this->view()
            ->layout('admin::layouts.app', ['breadcrumbs' => [['label' => __('admin::admin.admin'), 'url' => route('admin.dashboard')], ['label' => __('admin::dashboard.tab_title'), 'last' => true]]])
            ->title(__('admin::dashboard.tab_title'));
    }
};

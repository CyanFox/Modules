<?php

use Illuminate\Contracts\View\View;
use Livewire\Component;

new class extends Component {
    public function render(): View
    {
        return $this->view()
            ->layout('admin::layouts.app', ['breadcrumbs' => [['label' => __('admin::admin.admin'), 'url' => route('admin.dashboard')],
                ['label' => __('admin::settings.tab_title'), 'url' => route('admin.settings')], ['label' => __('admin::settings.module.tab_title'), 'last' => true]]])
            ->title(__('admin::settings.module.tab_title'));
    }
};

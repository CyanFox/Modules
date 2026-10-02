<?php

use Livewire\Component;

new class extends Component {

    public function render()
    {
        return $this->view()
            ->layout('dashboard::layouts.app', ['breadcrumbs' => [['label' => __('dashboard::dashboard.tab_title'), 'url' => route('dashboard'), 'last' => true]]])
            ->title(__('dashboard::dashboard.tab_title'));
    }
};

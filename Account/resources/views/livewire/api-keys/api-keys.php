<?php

use Livewire\Component;

new class extends Component {
    public function render()
    {
        return $this->view()
            ->layout('dashboard::layouts.app', ['breadcrumbs' => [['label' => __('account::account.account'), 'url' => route('account.profile')], ['label' => __('account::account.api'), 'last' => true]]])
            ->title(__('account::account.api'));
    }
};

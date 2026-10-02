<?php

use Livewire\Component;

new class extends Component {

    public function render()
    {
        return $this->view()
            ->layout('dashboard::layouts.app')
            ->title(__('account::profile.tab_title'));
    }
};

<?php

use Livewire\Component;
use Modules\Auth\Facades\Unsplash;

new class extends Component {
    public $unsplash = [];

    public function mount()
    {
        $this->unsplash = Unsplash::returnBackground();
    }

    public function render()
    {
        return $this->view()
            ->layout('auth::layouts.guest')
            ->title(__('auth::forgot-password.tab_title'));
    }
};

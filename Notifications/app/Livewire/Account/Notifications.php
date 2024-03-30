<?php

namespace Modules\Notifications\app\Livewire\Account;

use Livewire\Component;

class Notifications extends Component
{
    public function render()
    {
        return view('notifications::livewire.account.notifications')
            ->layout('components.layouts.app', ['title' => __('notifications::notifications.user_notifications.tab_title')]);
    }
}

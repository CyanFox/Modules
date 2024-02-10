<?php

namespace Modules\NotificationModule\app\Livewire\Account;

use Livewire\Component;

class Notifications extends Component
{
    public function render()
    {
        return view('notificationmodule::livewire.account.notifications')
            ->layout('components.layouts.app', ['title' => __('navigation/titles.notifications')]);
    }
}

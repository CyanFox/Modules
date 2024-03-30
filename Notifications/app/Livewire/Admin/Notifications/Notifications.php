<?php

namespace Modules\Notifications\app\Livewire\Admin\Notifications;

use Livewire\Component;

class Notifications extends Component
{
    public function render()
    {
        return view('notifications::livewire.admin.notifications.notifications')
            ->layout('components.layouts.admin', ['title' => __('notifications::notifications.notification_list.tab_title')]);
    }
}

<?php

namespace Modules\NotificationModule\app\Livewire\Admin\Notifications;

use Livewire\Component;

class Notifications extends Component
{
    public function render()
    {
        return view('notificationmodule::livewire.admin.notifications.notifications')
            ->layout('components.layouts.admin', ['title' => __('notificationmodule::navigation/titles.admin.notifications.create_notification')]);
    }
}

<?php

namespace Modules\NotificationModule\Livewire\Admin;

use App\Services\LWComponent;

class Notifications extends LWComponent
{
    public function render()
    {
        return $this->renderView('notificationmodule::livewire.admin.notifications', __('notificationmodule::notifications.list.tab_title'), 'adminmodule::components.layouts.app');
    }
}

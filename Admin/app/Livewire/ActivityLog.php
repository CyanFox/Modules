<?php

namespace Modules\Admin\Livewire;

use App\Livewire\CFComponent;

class ActivityLog extends CFComponent
{
    public function render()
    {
        return $this->renderView('admin::livewire.activity-log', __('admin::activity.tab_title'), 'admin::components.layouts.app');
    }
}

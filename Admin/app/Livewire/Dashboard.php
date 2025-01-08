<?php

namespace Modules\Admin\Livewire;

use App\Livewire\CFComponent;

class Dashboard extends CFComponent
{
    public function render()
    {
        return $this->renderView('admin::livewire.dashboard', __('admin::dashboard.tab_title'), 'admin::components.layouts.app');
    }
}

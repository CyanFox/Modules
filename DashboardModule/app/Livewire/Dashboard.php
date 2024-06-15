<?php

namespace Modules\DashboardModule\Livewire;

use App\Services\LWComponent;

class Dashboard extends LWComponent
{
    public function render()
    {
        return $this->renderView('dashboardmodule::livewire.dashboard', __('dashboardmodule::dashboard.tab_titles.dashboard'), 'dashboardmodule::components.layouts.app');
    }
}

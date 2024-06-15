<?php

namespace Modules\DashboardModule\Livewire;

use App\Services\LWComponent;

class Home extends LWComponent
{
    public function render()
    {
        return $this->renderView('dashboardmodule::livewire.home', __('dashboardmodule::dashboard.tab_titles.home'), 'dashboardmodule::components.layouts.guest');
    }
}

<?php

namespace Modules\TelemetryModule\Livewire;

use App\Facades\ModuleManager;
use App\Services\LWComponent;

class Telemetry extends LWComponent
{
    public function render()
    {
        if (ModuleManager::getModule('DashboardModule')->isEnabled()) {
            return $this->renderView('telemetrymodule::livewire.telemetry', __('telemetrymodule::telemetry.tab_title'), 'dashboardmodule::components.layouts.app');
        }
        return $this->renderView('telemetrymodule::livewire.telemetry', __('telemetrymodule::telemetry.tab_title'));
    }
}

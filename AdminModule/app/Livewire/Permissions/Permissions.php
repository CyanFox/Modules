<?php

namespace Modules\AdminModule\Livewire\Permissions;

use App\Services\LWComponent;

class Permissions extends LWComponent
{
    public function render()
    {
        return $this->renderView('adminmodule::livewire.permissions.permissions', __('adminmodule::permissions.tab_title'), 'adminmodule::components.layouts.app');
    }
}

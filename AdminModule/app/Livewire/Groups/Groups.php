<?php

namespace Modules\AdminModule\Livewire\Groups;

use App\Services\LWComponent;

class Groups extends LWComponent
{
    public function render()
    {
        return $this->renderView('adminmodule::livewire.groups.groups', __('adminmodule::groups.tab_title'), 'adminmodule::components.layouts.app');
    }
}

<?php

namespace Modules\Admin\Livewire\Permissions;

use App\Livewire\CFComponent;

class Permissions extends CFComponent
{
    public function render()
    {
        return $this->renderView('admin::livewire.permissions.permissions', __('admin::permissions.tab_title'), 'admin::components.layouts.app');
    }
}

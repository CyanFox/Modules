<?php

namespace Modules\Admin\Livewire\Groups;

use App\Livewire\CFComponent;

class Groups extends CFComponent
{
    public function render()
    {
        return $this->renderView('admin::livewire.groups.groups', __('admin::groups.tab_title'), 'admin::components.layouts.app');
    }
}

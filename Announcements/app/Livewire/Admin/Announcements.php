<?php

namespace Modules\Announcements\Livewire\Admin;

use App\Livewire\CFComponent;

class Announcements extends CFComponent
{
    public function render()
    {
        return $this->renderView('announcements::livewire.admin.announcements', __('announcements::announcements.tab_title'), 'admin::components.layouts.app');
    }
}

<?php

namespace Modules\Redirects\Livewire;

use App\Livewire\CFComponent;

class Redirects extends CFComponent
{
    public function render()
    {
        return $this->renderView('redirects::livewire.redirects', __('redirects::redirects.tab_title'), 'dashboard::components.layouts.app');
    }
}

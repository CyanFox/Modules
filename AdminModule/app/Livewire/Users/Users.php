<?php

namespace Modules\AdminModule\Livewire\Users;

use App\Services\LWComponent;

class Users extends LWComponent
{
    public function render()
    {
        return $this->renderView('adminmodule::livewire.users.users', __('adminmodule::users.tab_title'), 'adminmodule::components.layouts.app');
    }
}

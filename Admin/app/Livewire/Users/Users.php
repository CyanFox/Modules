<?php

namespace Modules\Admin\Livewire\Users;

use App\Livewire\CFComponent;

class Users extends CFComponent
{
    public function render()
    {
        return $this->renderView('admin::livewire.users.users', __('admin::users.tab_title'), 'admin::components.layouts.app');
    }
}

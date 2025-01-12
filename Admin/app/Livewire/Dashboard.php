<?php

namespace Modules\Admin\Livewire;

use App\Facades\VersionManager;
use App\Livewire\CFComponent;
use App\Traits\WithCustomLivewireException;

class Dashboard extends CFComponent
{
    use WithCustomLivewireException;

    public $isDevVersion;

    public function mount(): void
    {
        $this->isDevVersion = VersionManager::isDevVersion();
    }

    public function render()
    {
        return $this->renderView('admin::livewire.dashboard', __('admin::dashboard.tab_title'), 'admin::components.layouts.app');
    }
}

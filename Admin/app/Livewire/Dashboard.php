<?php

namespace Modules\Admin\Livewire;

use App\Facades\VersionManager;
use App\Livewire\CFComponent;
use App\Traits\WithCustomLivewireException;
use Illuminate\Support\Facades\Cache;

class Dashboard extends CFComponent
{
    use WithCustomLivewireException;

    public $isDevVersion;
    public $currentBaseVersion;
    public $remoteBaseVersion;
    public $upToDate;

    public function mount(): void
    {
        $this->isDevVersion = VersionManager::isDevVersion();
        $this->currentBaseVersion = VersionManager::getCurrentBaseVersion();
        $this->remoteBaseVersion = Cache::remember('admin.dashboard.remote_version', 60 * 60, function () {
            return VersionManager::getRemoteBaseVersion();
        });
        $this->upToDate = VersionManager::isBaseUpToDate();
    }

    public function render()
    {
        return $this->renderView('admin::livewire.dashboard', __('admin::dashboard.tab_title'), 'admin::components.layouts.app');
    }
}

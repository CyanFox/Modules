<?php

namespace Modules\AdminModule\Livewire;

use App\Facades\ModuleManager;
use App\Facades\Utils\VersionManager;
use App\Services\LWComponent;

class AdminDashboard extends LWComponent
{
    public $currentProjectVersion;

    public $currentTemplateVersion;

    public $currentAdminModuleVersion;

    public $remoteProjectVersion;

    public $remoteTemplateVersion;

    public $isProjectUpToDate;

    public $isTemplateUpToDate;

    public $isDevVersion;

    public $showUpdateNotification = false;

    public function checkForUpdates(): void
    {
        $this->remoteProjectVersion = VersionManager::getRemoteProjectVersion();
        $this->remoteTemplateVersion = VersionManager::getRemoteTemplateVersion();
        $this->isProjectUpToDate = VersionManager::isProjectUpToDate();
        $this->isTemplateUpToDate = VersionManager::isTemplateUpToDate();
        $this->showUpdateNotification = true;
    }

    public function mount(): void
    {
        $this->isDevVersion = VersionManager::isDevVersion();
        $this->currentProjectVersion = VersionManager::getCurrentProjectVersion();
        $this->currentTemplateVersion = VersionManager::getCurrentTemplateVersion();
        $this->currentAdminModuleVersion = ModuleManager::getModule('AdminModule')->getVersion();
    }

    public function render()
    {
        return $this->renderView('adminmodule::livewire.admin-dashboard', __('adminmodule::dashboard.tab_title'), 'adminmodule::components.layouts.app');
    }
}

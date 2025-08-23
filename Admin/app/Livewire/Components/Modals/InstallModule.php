<?php

namespace Modules\Admin\Livewire\Components\Modals;

use App\Facades\ModuleManager;
use App\Livewire\CFModalComponent;
use Livewire\WithFileUploads;
use Masmerise\Toaster\Toaster;

class InstallModule extends CFModalComponent
{
    use WithFileUploads;

    public $moduleFile;

    public $moduleUrl;

    public function installModule()
    {
        if (auth()->user()->cannot('admin.modules.install')) {
            return;
        }

        if ($this->moduleUrl) {
            $this->validate([
                'moduleUrl' => 'required|url',
            ]);

            if (! ModuleManager::installModuleFromURL($this->moduleUrl)) {
                Toaster::error(__('messages.notifications.something_went_wrong'));

                $this->log('Could not install module. Try again with a different module.', 'error');
            } else {
                Toaster::success(__('admin::modules.install_module.notifications.module_installed'));

                $this->redirect(route('admin.modules'), true);
            }

            return;
        }

        $this->validate([
            'moduleFile' => 'required|file|mimes:zip',
        ]);

        $path = $this->moduleFile->store('temp');
        if (! ModuleManager::installModule('app/private/'.$path)) {
            Toaster::error(__('messages.notifications.something_went_wrong'));

            $this->log('Could not install module. Try again with a different module.', 'error');
        } else {
            Toaster::success(__('admin::modules.install_module.notifications.module_installed'));

            $this->redirect(route('admin.modules'), true);
        }
    }

    public function render()
    {
        return view('admin::livewire.components.modals.install-module');
    }
}

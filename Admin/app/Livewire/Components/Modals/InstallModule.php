<?php

namespace Modules\Admin\Livewire\Components\Modals;

use App\Facades\ModuleManager;
use App\Livewire\CFModalComponent;
use Filament\Notifications\Notification;
use Livewire\WithFileUploads;

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
                Notification::make()
                    ->title(__('messages.notifications.something_went_wrong'))
                    ->danger()
                    ->send();

                $this->log('Could not install module. Try again with a different module.', 'error');
            } else {
                Notification::make()
                    ->title(__('admin::modules.install_module.notifications.module_installed'))
                    ->success()
                    ->send();

                $this->redirect(route('admin.modules'), true);
            }

            return;
        }

        $this->validate([
            'moduleFile' => 'required|file|mimes:zip',
        ]);

        $path = $this->moduleFile->store('temp');
        if (! ModuleManager::installModule('app/private/'.$path)) {
            Notification::make()
                ->title(__('messages.notifications.something_went_wrong'))
                ->danger()
                ->send();

            $this->log('Could not install module. Try again with a different module.', 'error');
        } else {
            Notification::make()
                ->title(__('admin::modules.install_module.notifications.module_installed'))
                ->success()
                ->send();

            $this->redirect(route('admin.modules'), true);
        }
    }

    public function render()
    {
        return view('admin::livewire.components.modals.install-module');
    }
}

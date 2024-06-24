<?php

namespace Modules\AdminModule\Livewire\Components\Modals;

use App\Facades\ModuleManager;
use App\Services\LWComponent;
use Filament\Notifications\Notification;
use Livewire\Attributes\On;
use Livewire\WithFileUploads;

class InstallModule extends LWComponent
{
    use WithFileUploads;

    public $moduleFile;

    public $moduleUrl;

    public $installModal;

    public function installModule()
    {
        if ($this->moduleUrl) {
            $this->validate([
                'moduleUrl' => 'required|url',
            ]);

            if (!ModuleManager::installModuleFromURL($this->moduleUrl)) {
                Notification::make()
                    ->title(__('messages.notifications.something_went_wrong'))
                    ->danger()
                    ->send();

                $this->log('Could not install module. Try again with a different module.', 'error');
                $this->installModal = false;
            } else {
                Notification::make()
                    ->title(__('adminmodule::modules.install_module.notifications.module_installed'))
                    ->success()
                    ->send();

                $this->redirect(route('admin.modules'), navigate: true);
            }

            return;
        }

        $this->validate([
            'moduleFile' => 'required|file|mimes:zip',
        ]);

        $path = $this->moduleFile->store('temp');
        if (!ModuleManager::installModule('app/' . $path)) {
            Notification::make()
                ->title(__('messages.notifications.something_went_wrong'))
                ->danger()
                ->send();

            $this->log('Could not install module. Try again with a different module.', 'error');
            $this->installModal = false;
        } else {
            Notification::make()
                ->title(__('adminmodule::modules.install_module.notifications.module_installed'))
                ->success()
                ->send();

            $this->redirect(route('admin.modules'), navigate: true);
        }
    }

    #[On('toggleInstallModal')]
    public function toggleInstallModal()
    {
        $this->installModal = !$this->installModal;
    }

    public function render()
    {
        return view('adminmodule::livewire.components.modals.install-module');
    }
}

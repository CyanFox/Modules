<?php

namespace Modules\AdminModule\Livewire;

use App\Facades\ModuleManager;
use App\Models\Setting;
use App\Services\LWComponent;
use Exception;
use Filament\Notifications\Notification;
use Nwidart\Modules\Facades\Module;
use Symfony\Component\Process\Process;
use TallStackUi\Traits\Interactions;

class Modules extends LWComponent
{
    use Interactions;

    public $moduleList;

    public $moduleSearchKeyword;

    public function enableModule($moduleName)
    {
        ModuleManager::getModule($moduleName)->enable();

        Notification::make()
            ->title(__('adminmodule::modules.notifications.module_enabled'))
            ->success()
            ->send();
    }

    public function disableModule($moduleName)
    {
        ModuleManager::getModule($moduleName)->disable();

        Notification::make()
            ->title(__('adminmodule::modules.notifications.module_disabled'))
            ->success()
            ->send();
    }

    public function deleteModule($moduleName, $confirmed = true)
    {
        if ($confirmed) {
            try {
                Setting::where('key', 'LIKE', strtolower($moduleName) . '%')->delete();

                $module = Module::find($moduleName);
                $module->delete();
            } catch (Exception $e) {
                Notification::make()
                    ->title(__('messages.notifications.something_went_wrong'))
                    ->danger()
                    ->send();

                $this->log($e->getMessage(), 'error');

                $this->redirect(route('admin.modules'), navigate: true);

                return;
            }

            Notification::make()
                ->title(__('adminmodule::modules.delete_module.notifications.module_deleted'))
                ->success()
                ->send();

            return;
        }

        $this->dialog()
            ->error(__('adminmodule::modules.delete_module.title'), __('adminmodule::modules.delete_module.description'))
            ->confirm(__('messages.buttons.delete'), 'deleteModule', $moduleName)
            ->cancel()
            ->send();

    }

    public function runMigrations($moduleName)
    {
        try {
            ModuleManager::getModule($moduleName)->runMigrations();
        } catch (Exception $e) {
            Notification::make()
                ->title(__('messages.notifications.something_went_wrong'))
                ->danger()
                ->send();

            $this->log($e->getMessage(), 'error');

            return;
        }

        Notification::make()
            ->title(__('adminmodule::modules.notifications.module_migrated'))
            ->success()
            ->send();
    }

    public function runComposer($moduleName)
    {
        try {
            $process = new Process(['composer', 'update'], Module::getModulePath($moduleName));
            $process->run();
        } catch (Exception $e) {
            Notification::make()
                ->title(__('messages.notifications.something_went_wrong'))
                ->danger()
                ->send();

            $this->log($e->getMessage(), 'error');

            return;
        }

        Notification::make()
            ->title(__('adminmodule::modules.notifications.module_composer_updated'))
            ->success()
            ->send();
    }

    public function runNpm($moduleName)
    {
        try {
            $process = new Process(['npm', 'install'], Module::getModulePath($moduleName));
            $process->run();

            $process = new Process(['npm', 'run', 'build'], Module::getModulePath($moduleName));
            $process->run();
        } catch (Exception $e) {
            Notification::make()
                ->title(__('messages.notifications.something_went_wrong'))
                ->danger()
                ->send();

            $this->log($e->getMessage(), 'error');

            return;
        }

        Notification::make()
            ->title(__('adminmodule::modules.notifications.module_npm_built'))
            ->success()
            ->send();
    }

    public function searchModule()
    {
        $moduleList = ModuleManager::getModules();
        $results = [];
        foreach ($moduleList as $module) {
            if (str_contains($module->getName(), $this->moduleSearchKeyword)) {
                $results[] = $module->getName();
            }
        }

        $this->moduleList = $results;
    }

    public function mount()
    {
        $moduleList = ModuleManager::getModules();
        foreach ($moduleList as $module) {
            $this->moduleList[] = $module->getName();
        }
    }

    public function render()
    {
        return $this->renderView('adminmodule::livewire.modules', __('adminmodule::settings.tab_title'), 'adminmodule::components.layouts.app');
    }
}

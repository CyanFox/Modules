<?php

namespace Modules\Admin\Livewire\Modules;

use App\Facades\ModuleManager;
use App\Livewire\CFComponent;
use App\Models\Setting;
use App\Traits\WithCustomLivewireException;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Artisan;
use Modules\Auth\Traits\WithConfirmation;
use Nwidart\Modules\Facades\Module;
use Symfony\Component\Process\Process;

class Modules extends CFComponent
{
    use WithConfirmation, WithCustomLivewireException;

    public $moduleList;

    public $moduleSearchKeyword;

    public function enableModule($moduleName)
    {
        if (auth()->user()->cannot('admin.modules.enable')) {
            return;
        }

        if (!(ModuleManager::checkRequirements($moduleName) && ModuleManager::checkBaseVersion($moduleName))) {
            Notification::make()
                ->title(__('admin::modules.notifications.module_requirements_not_met'))
                ->danger()
                ->send();

            $this->redirect(route('admin.modules'), navigate: true);

            return;
        }

        ModuleManager::getModule($moduleName)->enable();

        Artisan::call('module:migrate', ['module' => $moduleName]);
        Artisan::call('module:publish', ['module' => $moduleName]);
        Artisan::call('cache:clear');

        $files = glob(base_path('bootstrap/cache/*.php'));
        foreach ($files as $file) {
            if (is_file($file)) {
                unlink($file);
            }
        }

        Process::fromShellCommandline('composer update')->run();
        Process::fromShellCommandline('npm run build')->run();

        activity()
            ->causedBy(auth()->user())
            ->log('module.' . strtolower($moduleName) . '.enabled');

        Notification::make()
            ->title(__('admin::modules.notifications.module_enabled'))
            ->success()
            ->send();

        $this->redirect(route('admin.modules'), navigate: true);
    }

    public function disableModule($moduleName)
    {
        if (auth()->user()->cannot('admin.modules.disable')) {
            return;
        }

        foreach (Module::all() as $module) {
            if (in_array($moduleName, ModuleManager::getRequirements($module->getName()))) {
                Notification::make()
                    ->title(__('admin::modules.notifications.module_required_by_other_module'))
                    ->danger()
                    ->send();

                $this->redirect(route('admin.modules'), navigate: true);

                return;
            }
        }

        ModuleManager::getModule($moduleName)->disable();

        $files = glob(base_path('bootstrap/cache/*.php'));
        foreach ($files as $file) {
            if (is_file($file)) {
                unlink($file);
            }
        }

        Process::fromShellCommandline('composer update')->run();
        Process::fromShellCommandline('npm run build')->run();

        activity()
            ->causedBy(auth()->user())
            ->log('module.' . strtolower($moduleName) . '.disabled');

        Notification::make()
            ->title(__('admin::modules.notifications.module_disabled'))
            ->success()
            ->send();

        $this->redirect(route('admin.modules'), navigate: true);
    }

    public function deleteModule($moduleName, $confirmed = true)
    {
        if (auth()->user()->cannot('admin.modules.delete')) {
            return;
        }

        foreach (Module::all() as $module) {
            if (in_array($moduleName, ModuleManager::getRequirements($module->getName()))) {
                Notification::make()
                    ->title(__('admin::modules.notifications.module_required_by_other_module'))
                    ->danger()
                    ->send();

                $this->redirect(route('admin.modules'), navigate: true);

                return;
            }
        }

        if ($confirmed) {
            Setting::where('key', 'LIKE', strtolower($moduleName) . '%')->delete();

            $module = Module::find($moduleName);
            $module->disable();
            $module->delete();

            $files = glob(base_path('bootstrap/cache/*.php'));
            foreach ($files as $file) {
                if (is_file($file)) {
                    unlink($file);
                }
            }

            Process::fromShellCommandline('composer update')->run();
            Process::fromShellCommandline('npm run build')->run();


            activity()
                ->causedBy(auth()->user())
                ->log('module.' . strtolower($moduleName) . '.deleted');

            Notification::make()
                ->title(__('admin::modules.delete_module.notifications.module_deleted'))
                ->success()
                ->send();

            $this->redirect(route('admin.modules'), navigate: true);

            return;
        }

        $this->dialog()
            ->question(__('admin::modules.delete_module.title'),
                __('admin::modules.delete_module.description'))
            ->icon('icon-triangle-alert')
            ->confirm(__('admin::modules.delete_module.buttons.delete_module'), 'danger')
            ->method('deleteModule', $moduleName)
            ->send();

    }

    public function searchModule()
    {
        $moduleList = Module::all();
        $results = [];
        foreach ($moduleList as $module) {
            if (str_contains($module, $this->moduleSearchKeyword)) {
                $results[] = $module->getName();
            }
        }

        $this->moduleList = $results;
    }

    public function mount()
    {
        $moduleList = Module::all();
        foreach ($moduleList as $module) {
            $this->moduleList[] = $module->getName();
        }
    }

    public function render()
    {
        return $this->renderView('admin::livewire.modules.modules', __('admin::modules.tab_title'), 'admin::components.layouts.app');
    }
}

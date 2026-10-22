<?php

use Illuminate\Contracts\View\View;
use Livewire\Component;
use Modules\Core\Enums\SettingsProperty;
use Modules\Core\Models\Setting;
use Modules\Core\Models\UserSetting;
use Modules\Core\Traits\WithConfirmation;
use Nwidart\Modules\Facades\Module;
use Symfony\Component\Process\Process;

new class extends Component {
    use WithConfirmation;

    public function enableModule($module)
    {
        if (auth()->user()->cannot('admin.modules.enable')) {
            return;
        }

        if (!modules()->checkRequirements($module)) {
            Toaster::error(__('admin::modules.notifications.requirements_not_met'));
            return;
        }

        modules()->getModule($module)->enable();

        Artisan::call('module:migrate', ['module' => $module]);
        Artisan::call('module:publish', ['module' => $module]);
        Artisan::call('optimize:clear');

        $files = glob(base_path('bootstrap/cache/*.php'));
        foreach ($files as $file) {
            if (is_file($file)) {
                unlink($file);
            }
        }

        Process::fromShellCommandline('composer update')->run();
        Process::fromShellCommandline('npm i')->run();
        Process::fromShellCommandline('npm run build')->run();

        activity()
            ->causedBy(auth()->user())
            ->log('admin.modules.' . mb_strtolower($module) . '.enabled');

        Toaster::success(__('admin::modules.notifications.enabled'));

        $this->redirect(url()->previous(), true);
    }

    public function disableModule($module)
    {
        if (auth()->user()->cannot('admin.modules.disable')) {
            return;
        }

        foreach (Module::all() as $m) {
            if (in_array($module, modules()->getRequirements($m->getName()))) {
                Toaster::error(__('admin::modules.notifications.required_by_another_module'));
                return;
            }
        }

        modules()->getModule($module)->disable();

        $files = glob(base_path('bootstrap/cache/*.php'));
        foreach ($files as $file) {
            if (is_file($file)) {
                unlink($file);
            }
        }

        Process::fromShellCommandline('composer update')->run();
        Process::fromShellCommandline('npm i')->run();
        Process::fromShellCommandline('npm run build')->run();

        activity()
            ->causedBy(auth()->user())
            ->log('admin.modules.' . mb_strtolower($module) . '.disabled');

        Toaster::success(__('admin::modules.notifications.disabled'));

        $this->redirect(url()->previous(), true);
    }

    public function deleteModule($module, $confirmed = false)
    {
        if (auth()->user()->cannot('admin.modules.delete')) {
            return;
        }

        foreach (Module::all() as $m) {
            if (in_array($module, modules()->getRequirements($m->getName()))) {
                Toaster::error(__('admin::modules.notifications.required_by_another_module'));
                return;
            }
        }

        if ($confirmed) {
            foreach (Setting::all() as $setting) {
                if ($setting->hasProperty(SettingsProperty::MODULE) && $setting->getProperty(SettingsProperty::MODULE) == $module) {
                    $setting->delete();
                }
            }
            foreach (UserSetting::all() as $setting) {
                if ($setting->hasProperty(SettingsProperty::MODULE) && $setting->getProperty(SettingsProperty::MODULE) == $module) {
                    $setting->delete();
                }
            }

            $m = Module::find($module);
            $m->disable();
            $m->delete();

            $files = glob(base_path('bootstrap/cache/*.php'));
            foreach ($files as $file) {
                if (is_file($file)) {
                    unlink($file);
                }
            }

            Process::fromShellCommandline('composer update')->run();
            Process::fromShellCommandline('npm i')->run();
            Process::fromShellCommandline('npm run build')->run();

            activity()
                ->causedBy(auth()->user())
                ->log('admin.modules.' . mb_strtolower($module) . '.deleted');

            Toaster::success(__('admin::modules.delete.notifications.deleted'));

            $this->redirect(url()->previous(), true);

            return;
        }

        $this->dialog()
            ->question(__('admin::modules.delete.title'),
                __('admin::modules.delete.description'))
            ->icon('icon-triangle-alert')
            ->confirm(__('admin::modules.delete.buttons.delete'), 'danger')
            ->method('deleteModule', $module, true)
            ->send();

    }

    public function render(): View
    {
        return $this->view()
            ->layout('admin::layouts.app', ['breadcrumbs' => [['label' => __('admin::admin.admin'), 'url' => route('admin.dashboard')], ['label' => __('admin::modules.tab_title'), 'last' => true]]])
            ->title(__('admin::modules.tab_title'));
    }
};

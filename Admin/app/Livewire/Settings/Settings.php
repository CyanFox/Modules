<?php

namespace Modules\Admin\Livewire\Settings;

use App\Facades\ModuleManager;
use App\Facades\SettingsManager;
use App\Livewire\CFComponent;
use App\Models\Setting;
use App\Traits\WithCustomLivewireException;
use Exception;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Url;
use Livewire\WithFileUploads;
use Nwidart\Modules\Facades\Module;

class Settings extends CFComponent
{
    use WithCustomLivewireException, WithFileUploads;

    #[Url]
    public $tab;

    public $appName;
    public $appUrl;
    public $appTimezone;
    public $appLanguage;
    public $baseVersionUrl;
    public $logo;
    public $moduleList;
    public $moduleSearch;
    public $editorEncryption;
    public $editorDecryption;
    public $editorSearch;
    public $originalEditorSettings;
    public $editorSettings;

    public function updateGeneralSettings()
    {
        $this->validate([
            'appName' => 'required|string',
            'appUrl' => 'required|url',
            'appTimezone' => 'required|string',
            'appLanguage' => 'required|string',
            'baseVersionUrl' => 'required|url',
            'logo' => 'nullable|image',
        ]);

        settings()->updateSettings([
            'internal.app.name' => $this->appName,
            'internal.app.url' => $this->appUrl,
            'internal.app.timezone' => $this->appTimezone,
            'internal.app.lang' => $this->appLanguage,
            'internal.versions.base_url' => $this->baseVersionUrl,
        ]);

        if ($this->logo) {
            Storage::disk('public')->delete('img/' . str_replace('/storage/img/', '', settings('internal.app.logo')));

            $this->logo->storeAs('img', 'Logo.' . $this->logo->getClientOriginalExtension(), 'public');

            settings()->updateSetting('internal.app.logo', '/storage/img/Logo.' . $this->logo->getClientOriginalExtension());
        }

        Notification::make()
            ->title(__('admin::settings.notifications.settings_updated'))
            ->success()
            ->send();

        $this->redirect(route('admin.settings', ['tab' => 'general']), true);
    }

    public function resetLogo()
    {
        Storage::disk('public')->delete('img/' . str_replace('/storage/img/', '', settings('internal.app.logo')));

        settings()->updateSetting('internal.app.logo', '/img/Logo.svg');

        $this->redirect(route('admin.settings', ['tab' => 'general']), true);
    }


    public function searchModule()
    {
        $moduleList = ModuleManager::getModules();
        $results = [];
        foreach ($moduleList as $module) {
            if (str_contains($module->getName(), $this->moduleSearch)) {
                $results[] = $module->getName();
            }
        }

        $this->moduleList = $results;
    }

    public function updateEditorSettings()
    {
        $settings = [];
        foreach ($this->editorSettings as $key => $value) {
            $newKey = str_replace(':', '.', $key);
            $settings[$newKey] = $value;
        }

        Setting::truncate();
        SettingsManager::updateSettings($settings);

        Notification::make()
            ->title(__('admin::settings.notifications.settings_updated'))
            ->success()
            ->send();

        $this->redirect(route('admin.settings', ['tab' => 'editor']), navigate: true);
    }

    public function searchEditorSetting()
    {
        $keyword = $this->editorSearch;

        $this->originalEditorSettings = Setting::where('key', 'like', "%$keyword%")->get()->mapWithKeys(function ($setting) {
            return [$setting->key => ['value' => $setting->value, 'is_locked' => $setting->is_locked]];
        });
    }

    public function cryptEditorSetting($type)
    {
        if ($type === 'encrypt') {
            $this->validate([
                'editorEncryption' => 'required',
            ]);

            $this->editorEncryption = encrypt($this->editorEncryption);
        } else {
            $this->validate([
                'editorDecryption' => 'required',
            ]);
            try {
                $this->editorDecryption = decrypt($this->editorDecryption);
            } catch (Exception) {
                return;
            }
        }
    }

    public function setLockState($key, $state)
    {

        $setting = Setting::where('key', $key)->first();
        $setting->is_locked = $state;
        $setting->save();

        $this->originalEditorSettings = Setting::all()->mapWithKeys(function ($setting) {
            return [$setting->key => ['value' => $setting->value, 'is_locked' => $setting->is_locked]];
        });
    }

    public function mount()
    {
        if (blank($this->tab)) {
            $this->tab = 'general';
        }

        $this->appName = settings('internal.app.name', config('app.name'));
        $this->appUrl = settings('internal.app.url', config('app.url'));
        $this->appTimezone = settings('internal.app.timezone', config('app.timezone'));
        $this->appLanguage = settings('internal.app.lang', config('app.locale'));
        $this->baseVersionUrl = settings('internal.versions.base_url', config('settings.base_url'));

        $moduleList = Module::allEnabled();
        foreach ($moduleList as $module) {
            $this->moduleList[] = $module->getName();
        }

        $this->originalEditorSettings = Setting::all()->mapWithKeys(function ($setting) {
            return [$setting->key => ['value' => $setting->value, 'is_locked' => $setting->is_locked]];
        });

        foreach ($this->originalEditorSettings as $key => $value) {
            $this->editorSettings[str_replace('.', ':', $key)] = $value['value'];
        }
    }

    public function render()
    {
        return $this->renderView('admin::livewire.settings.settings', __('admin::settings.tab_title'), 'admin::components.layouts.app');
    }
}

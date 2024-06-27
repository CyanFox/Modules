<?php

namespace Modules\AdminModule\Livewire;

use App\Facades\ModuleManager;
use App\Facades\Utils\SettingsManager;
use App\Models\Setting;
use App\Services\LWComponent;
use Exception;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Url;
use Livewire\WithFileUploads;

class Settings extends LWComponent
{
    use WithFileUploads;

    #[Url]
    public $tab;

    public $availableTimeZones;

    public $systemName;

    public $systemUrl;

    public $systemLang;

    public $systemTimeZone;

    public $unsplashUtm;

    public $unsplashApiKey;

    public $projectVersionUrl;

    public $templateVersionUrl;

    public $logoFile;

    public $moduleList;

    public $moduleSearchKeyword;

    public $editorEncryptionKeyword;

    public $editorDecryptionKeyword;

    public $editorSearchKeyword;

    public $originalEditorSettings;

    public $editorSettings;

    public function updateSystemSettings()
    {
        if (Auth::user()->cannot('adminmodule.settings.update')) {
            return;
        }

        $this->validate([
            'systemName' => 'required',
            'systemUrl' => 'required|url',
            'systemLang' => 'required',
            'systemTimeZone' => 'required',
            'unsplashUtm' => 'nullable',
            'unsplashApiKey' => 'nullable',
            'projectVersionUrl' => 'nullable|url',
            'templateVersionUrl' => 'nullable|url',
            'logoFile' => 'nullable|image|max:1024',
        ]);

        $settings = [
            'settings.name' => $this->systemName,
            'settings.url' => $this->systemUrl,
            'settings.lang' => $this->systemLang,
            'settings.timezone' => $this->systemTimeZone,
            'settings.unsplash.utm' => $this->unsplashUtm,
            'settings.unsplash.api_key' => $this->unsplashApiKey ? encrypt($this->unsplashApiKey) : null,
            'settings.versions.project_url' => $this->projectVersionUrl,
            'settings.versions.template_url' => $this->templateVersionUrl,
        ];

        try {
            if ($this->logoFile) {
                $this->logoFile->storeAs('public', 'img/Logo.png');
                $settings['settings.logo_path'] = 'storage/img/Logo.png';
            }

            SettingsManager::updateSettings($settings);
        } catch (Exception $e) {
            Notification::make()
                ->title(__('messages.notifications.something_went_wrong'))
                ->danger()
                ->send();

            $this->log($e->getMessage(), 'error');

            return;
        }

        Notification::make()
            ->title(__('adminmodule::settings.notifications.settings_updated'))
            ->success()
            ->send();

        $this->redirect(route('admin.settings'), navigate: true);
    }

    public function resetLogo()
    {
        if (Auth::user()->cannot('adminmodule.settings.update')) {
            return;
        }

        Storage::disk('public')->delete('img/Logo.png');

        SettingsManager::updateSetting('settings.logo_path', 'img/Logo.svg');

        $this->redirect(route('admin.settings'), navigate: true);

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

    public function updateEditorSettings()
    {
        if (Auth::user()->cannot('adminmodule.settings.editor.update')) {
            return;
        }

        $settings = [];
        foreach ($this->editorSettings as $key => $value) {
            $newKey = str_replace(':', '.', $key);
            $settings[$newKey] = $value;
        }

        try {
            Setting::truncate();
            SettingsManager::updateSettings($settings);
        } catch (Exception $e) {
            Notification::make()
                ->title(__('messages.notifications.something_went_wrong'))
                ->danger()
                ->send();

            $this->log($e->getMessage(), 'error');

            return;
        }

        Notification::make()
            ->title(__('adminmodule::settings.notifications.settings_updated'))
            ->success()
            ->send();

        $this->redirect(route('admin.settings', ['tab' => __('adminmodule::settings.tabs.editor')]), navigate: true);
    }

    public function searchEditorSetting()
    {
        if ($this->editorSearchKeyword === null || $this->editorSearchKeyword === '') {
            $this->editorSettings = '';
            $this->originalEditorSettings = Setting::all()->mapWithKeys(function ($setting) {
                return [$setting->key => $setting->value];
            });

            foreach ($this->originalEditorSettings as $key => $value) {
                $this->editorSettings .= $key . '::' . $value . "\n";
            }

            return;
        }

        $settingsArray = explode("\n", $this->editorSettings);
        $results = [];
        foreach ($settingsArray as $setting) {
            $setting = explode('::', $setting);
            if (count($setting) === 2) {
                if (str_contains($setting[0], $this->editorSearchKeyword) || str_contains($setting[1], $this->editorSearchKeyword)) {
                    $results[] = $setting[0] . '::' . $setting[1];
                }
            }
        }

        $this->editorSettings = implode("\n", $results);
    }

    public function cryptEditorSetting($type)
    {
        if ($type === 'encrypt') {
            $this->validate([
                'editorEncryptionKeyword' => 'required',
            ]);

            try {
                $this->editorEncryptionKeyword = encrypt($this->editorEncryptionKeyword);
            } catch (Exception) {
                return;
            }
        } else {
            $this->validate([
                'editorDecryptionKeyword' => 'required',
            ]);
            try {
                $this->editorDecryptionKeyword = decrypt($this->editorDecryptionKeyword);
            } catch (Exception) {
                return;
            }
        }
    }

    public function mount()
    {
        if (!$this->tab) {
            $this->tab = __('adminmodule::settings.tabs.system');
        }

        $timeZones = timezone_identifiers_list();
        $results = [];
        foreach ($timeZones as $timeZone) {
            $results[] = ['label' => $timeZone, 'value' => $timeZone];
        }

        $this->availableTimeZones = $results;

        $this->systemName = setting('settings.name');
        $this->systemUrl = setting('settings.url');
        $this->systemLang = setting('settings.lang');
        $this->systemTimeZone = setting('settings.timezone');

        $this->unsplashUtm = setting('settings.unsplash.utm');
        $this->unsplashApiKey = setting('settings.unsplash.api_key', true);

        $this->projectVersionUrl = setting('settings.versions.project_url');
        $this->templateVersionUrl = setting('settings.versions.template_url');

        $moduleList = ModuleManager::getModules();
        foreach ($moduleList as $module) {
            $this->moduleList[] = $module->getName();
        }

        $this->originalEditorSettings = Setting::all()->mapWithKeys(function ($setting) {
            return [$setting->key => $setting->value];
        });

        foreach ($this->originalEditorSettings as $key => $value) {
            $this->editorSettings[str_replace('.', ':', $key)] = $value;
        }

    }

    public function render()
    {
        return $this->renderView('adminmodule::livewire.settings', __('adminmodule::settings.tab_title'), 'adminmodule::components.layouts.app');
    }
}

<?php

namespace Modules\Admin\Livewire\Settings;

use App\Facades\SettingsManager;
use App\Livewire\CFComponent;
use App\Models\Setting;
use App\Traits\WithCustomLivewireException;
use Exception;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Livewire\Attributes\Url;
use Livewire\WithFileUploads;
use Masmerise\Toaster\Toaster;
use Modules\Auth\Traits\WithConfirmation;
use Nwidart\Modules\Facades\Module;

class Settings extends CFComponent
{
    use WithConfirmation, WithCustomLivewireException, WithFileUploads;

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

    public $newSettingKey;

    public $newSettingValue;

    public $encryptedSettings = [];

    public $editorSettingsMap = [];

    public function updateGeneralSettings()
    {
        if (auth()->user()->cannot('admin.settings.update')) {
            return;
        }

        $this->validate([
            'appName' => 'required|string',
            'appUrl' => 'required|url',
            'appTimezone' => 'required|string',
            'appLanguage' => 'required|string',
            'baseVersionUrl' => 'required|url',
            'logo' => 'nullable|image:allow_svg',
        ]);

        settings()->updateSettings([
            'internal.app.name' => $this->appName,
            'internal.app.url' => $this->appUrl,
            'internal.app.timezone' => $this->appTimezone,
            'internal.app.lang' => $this->appLanguage,
            'internal.versions.base_url' => $this->baseVersionUrl,
        ]);

        if ($this->logo) {
            Storage::disk('public')->delete('img/'.str_replace('/storage/img/', '', settings('internal.app.logo')));

            $this->logo->storeAs('img', 'Logo.'.$this->logo->getClientOriginalExtension(), 'public');

            settings()->updateSetting('internal.app.logo', '/storage/img/Logo.'.$this->logo->getClientOriginalExtension());
        }

        activity()
            ->causedBy(auth()->user())
            ->log('settings.general.updated');

        Toaster::success(__('admin::settings.notifications.settings_updated'));

        $this->redirect(route('admin.settings', ['tab' => 'general']), true);
    }

    public function resetLogo()
    {
        if (auth()->user()->cannot('admin.settings.update')) {
            return;
        }

        Storage::disk('public')->delete('img/'.str_replace('/storage/img/', '', settings('internal.app.logo')));

        settings()->updateSetting('internal.app.logo', '/img/Logo.svg');

        activity()
            ->causedBy(auth()->user())
            ->log('settings.logo.reset');

        $this->redirect(route('admin.settings', ['tab' => 'general']), true);
    }

    public function searchModule()
    {
        $moduleList = Module::all();
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
        if (auth()->user()->cannot('admin.settings.update')) {
            return;
        }

        $settings = [];
        foreach ($this->editorSettings as $formKey => $value) {
            $dbKey = $this->editorSettingsMap[$formKey] ?? null;

            if ($dbKey && isset($this->originalEditorSettings[$dbKey])) {
                if (isset($this->encryptedSettings[$dbKey]) && ! $this->isEncrypted($value) && ! Str::contains($dbKey, ['key', 'password', 'secret', 'token'])) {
                    $value = encrypt($value);
                }

                $settings[$dbKey] = $value;
            }
        }

        foreach ($settings as $key => $value) {
            SettingsManager::updateSetting($key, $value);
        }

        activity()
            ->causedBy(auth()->user())
            ->log('settings.editor.updated');

        Toaster::success(__('admin::settings.notifications.settings_updated'));

        $this->redirect(route('admin.settings', ['tab' => 'editor']), navigate: true);
    }

    public function createSetting()
    {
        if (auth()->user()->cannot('admin.settings.update')) {
            return;
        }

        $this->validate([
            'newSettingKey' => 'required|string',
            'newSettingValue' => 'required|string',
        ]);

        settings()->updateSetting($this->newSettingKey, $this->newSettingValue);

        $this->newSettingKey = '';
        $this->newSettingValue = '';

        activity()
            ->causedBy(auth()->user())
            ->log('setting_created');

        Toaster::success(__('admin::settings.notifications.setting_created'));

        $this->redirect(route('admin.settings', ['tab' => 'editor']), true);
    }

    public function deleteSetting($key, $confirmed = true)
    {
        if (auth()->user()->cannot('admin.settings.update')) {
            return;
        }

        if (! $confirmed) {
            $this->dialog()
                ->question(__('admin::settings.delete_setting.title'),
                    __('admin::settings.delete_setting.description'))
                ->icon('icon-triangle-alert')
                ->confirm(__('admin::settings.delete_setting.buttons.delete_setting'), 'danger')
                ->method('deleteSetting', $key)
                ->send();

            return;
        }

        settings()->deleteSetting($key);

        activity()
            ->causedBy(auth()->user())
            ->log('setting_deleted');

        Toaster::success(__('admin::settings.delete_setting.notifications.setting_deleted'));

        $this->redirect(route('admin.settings', ['tab' => 'editor']), true);
    }

    public function searchEditorSetting()
    {
        $keyword = $this->editorSearch;
        $settings = Setting::where('key', 'like', "%$keyword%")->get();

        $this->loadEditorSettings($settings);
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
        if (auth()->user()->cannot('admin.settings.update')) {
            return;
        }

        $setting = Setting::where('key', $key)->first();
        $setting->is_locked = $state;
        $setting->save();

        activity()
            ->causedBy(auth()->user())
            ->log('settings_lock_'.($state ? 'enabled' : 'disabled'));

        $this->originalEditorSettings = Setting::all()->mapWithKeys(function ($setting) {
            return [$setting->key => ['value' => $setting->value, 'is_locked' => $setting->is_locked, 'is_textarea' => $setting->is_textarea]];
        });
    }

    public function setIsTextarea($key, $state)
    {
        if (auth()->user()->cannot('admin.settings.update')) {
            return;
        }

        $setting = Setting::where('key', $key)->first();
        $setting->is_textarea = $state;
        $setting->save();

        activity()
            ->causedBy(auth()->user())
            ->log('settings_textarea_'.($state ? 'enabled' : 'disabled'));

        $this->originalEditorSettings = Setting::all()->mapWithKeys(function ($setting) {
            return [$setting->key => ['value' => $setting->value, 'is_locked' => $setting->is_locked, 'is_textarea' => $setting->is_textarea]];
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

        $this->loadEditorSettings(Setting::all());
    }

    public function render()
    {
        return $this->renderView('admin::livewire.settings.settings', __('admin::settings.tab_title'), 'admin::components.layouts.app');
    }

    private function isEncrypted($value)
    {
        if (! is_string($value) || mb_strlen($value) <= 40) {
            return false;
        }
        try {
            decrypt($value);

            return true;
        } catch (Exception) {
            return false;
        }
    }

    private function loadEditorSettings($settingsCollection)
    {
        $this->originalEditorSettings = [];
        $this->editorSettings = [];
        $this->encryptedSettings = [];

        $settingsCollection->each(function ($setting, $index) {
            $key = $setting->key;
            $value = $setting->value;

            if ($this->isEncrypted($value)) {
                $this->encryptedSettings[$key] = true;
                $value = $this->tryDecrypt($value);
            }

            $this->originalEditorSettings[$key] = [
                'value' => $value,
                'is_textarea' => $setting->is_textarea,
                'is_locked' => $setting->is_locked,
            ];

            $formKey = 'setting_'.md5($key);
            $this->editorSettings[$formKey] = $value;

            $this->editorSettingsMap[$formKey] = $key;
        });
    }

    private function tryDecrypt($value)
    {
        try {
            return decrypt($value);
        } catch (Exception) {
            return $value;
        }
    }
}

<?php

use Illuminate\Contracts\View\View;
use Illuminate\Validation\ValidationException;
use Livewire\Component;
use Masmerise\Toaster\Toaster;
use Modules\Core\Enums\SettingsProperty;
use Modules\Core\Facades\Settings;
use Modules\Core\Models\Setting;
use Modules\Core\Traits\WithConfirmation;

new class extends Component {
    use WithConfirmation;

    public $settings = [];

    public function updateSettings(): void
    {
        $hasValidationErrors = false;
        $validationErrorSettings = [];

        foreach ($this->settings as $settingData) {
            $setting = Setting::findOrFail($settingData['id']);
            $properties = $this->normalizeProperties($settingData['properties'] ?? []);
            $originalProperties = $this->normalizeProperties($setting->properties ?? []);

            if ($setting->hasProperty(SettingsProperty::RULES)) {
                try {
                    $this->validate([
                        "settings.{$setting->id}.value" => $setting->getProperty(SettingsProperty::RULES),
                    ]);
                } catch (ValidationException $exception) {
                    $hasValidationErrors = true;
                    $langKey = $setting->getProperty(SettingsProperty::LANG_KEY);
                    $validationErrorSettings[] = $langKey ? __($langKey) : $setting->key;
                    continue;
                }
            }

            foreach ($properties as $property => $value) {
                if (array_key_exists($property, $originalProperties) && is_bool($originalProperties[$property])) {
                    $properties[$property] = $this->toBoolean($value);
                }
            }

            Settings::update(
                $setting->key,
                $this->castValue($settingData['value'], $properties[SettingsProperty::TYPE] ?? null),
                $properties,
            );
        }

        if ($hasValidationErrors) {
            Toaster::warning(__('admin::settings.notifications.validation_failed', [
                'settings' => implode(', ', $validationErrorSettings),
            ]));

            return;
        }

        Toaster::success(__('admin::settings.notifications.updated'));
        $this->redirect(url()->previous(), true);
    }

    public function deleteSetting($settingId, $confirmed = false)
    {
        if ($confirmed) {
            $setting = Setting::find($settingId);

            if (!$setting?->delete()) {
                Toaster::error(__('messages.notifications.something_went_wrong'));
                return;
            }

            Toaster::success(__('admin::settings.editor.delete.notifications.deleted'));

            $this->redirect(url()->previous(), true);

            return;
        }

        $this->dialog()
            ->question(__('admin::settings.editor.delete.title'),
                __('admin::settings.editor.delete.description'))
            ->icon('icon-triangle-alert')
            ->confirm(__('admin::settings.editor.buttons.delete'), 'danger')
            ->method('deleteSetting', $settingId, true)
            ->send();
    }

    private function castValue(mixed $value, ?string $type): mixed
    {
        return match ($type) {
            'int' => (int)$value,
            'float' => (float)$value,
            'bool' => filter_var($value, FILTER_VALIDATE_BOOLEAN),
            'array' => is_array($value) ? $value : (json_decode($value, true) ?? []),
            'json' => is_string($value) ? $value : json_encode($value),
            default => $value,
        };
    }

    private function formatValue(mixed $value, ?string $type): mixed
    {
        return in_array($type, ['array', 'json'], true) && is_array($value)
            ? json_encode($value, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
            : $value;
    }

    private function toBoolean(mixed $value): bool
    {
        return filter_var($value, FILTER_VALIDATE_BOOLEAN);
    }

    private function formatProperties(mixed $properties): array
    {
        $properties = $this->normalizeProperties($properties);

        foreach ($properties as $property => $value) {
            if (is_bool($value)) {
                $properties[$property] = $value ? '1' : '0';
            }
        }

        return $properties;
    }

    private function normalizeProperties(mixed $properties): array
    {
        if (is_string($properties)) {
            $properties = json_decode($properties, true);
        }

        return is_array($properties) ? $properties : [];
    }

    public function mount(): void
    {
        $this->settings = Setting::all()
            ->mapWithKeys(fn(Setting $setting) => [
                $setting->id => [
                    'id' => $setting->id,
                    'value' => $this->formatValue(Settings::get($setting->key), $setting->getProperty(SettingsProperty::TYPE)),
                    'properties' => $this->formatProperties($setting->properties ?? []),
                ],
            ])
            ->all();
    }

    public function render(): View
    {
        return $this->view()
            ->layout('admin::layouts.app', ['breadcrumbs' => [['label' => __('admin::admin.admin'), 'url' => route('admin.dashboard')],
                ['label' => __('admin::settings.tab_title'), 'url' => route('admin.settings')], ['label' => __('admin::settings.editor.tab_title'), 'last' => true]]])
            ->title(__('admin::settings.editor.tab_title'));
    }
};

<?php

declare(strict_types=1);

namespace Modules\Core\Services;

use Modules\Core\Enums\SettingsProperty;
use Modules\Core\Exceptions\SettingNotFoundException;
use Modules\Core\Models\Setting;

class SettingsService
{
    public function get(string $key, mixed $default = null): mixed
    {
        $setting = Setting::where('key', $key)->first();
        if (!$setting) {
            return $default;
        }

        $encrypted = $setting->hasProperty(SettingsProperty::ENCRYPTED->value);

        if ($encrypted) {
            $value = decrypt($setting->value);
        } else {
            $value = $setting->value;
        }

        return $this->convertTypes($value, $setting->getProperty(SettingsProperty::TYPE->value));
    }

    private function convertTypes(mixed $value, string $type): mixed
    {
        return match ($type) {
            'string' => (string)$value,
            'int' => (int)$value,
            'float' => (float)$value,
            'bool' => (bool)$value,
            'array' => (array)$value,
            'json' => json_decode($value, true),
            default => $value,
        };
    }

    private function getProperty(array $properties, string $property): mixed
    {
        return $properties[$property];
    }

    /**
     * @throws SettingNotFoundException
     */
    public function set(string $key, string $value, bool $updateIfExists = false, array $properties = [SettingsProperty::INTERNAL->value => true]): Setting
    {
        $setting = Setting::where('key', $key)->first();
        if (!$setting) {
            throw new SettingNotFoundException($key);
        }

        if ($updateIfExists) {
            $setting->update([
                'value' => $this->getProperty($properties, SettingsProperty::ENCRYPTED->value) ? encrypt($value) : $value,
                'properties' => json_encode($properties),
            ]);
        }

        if (blank($setting)) {
            $setting = Setting::create([
                'key' => $key,
                'value' => $this->getProperty($properties, SettingsProperty::ENCRYPTED->value) ? encrypt($value) : $value,
                'properties' => json_encode($properties),
            ]);
        }

        return $setting;
    }

    /**
     * @throws SettingNotFoundException
     */
    public function update(string $key, string $value, array $properties = [SettingsProperty::INTERNAL->value => true]): Setting
    {
        $setting = Setting::where('key', $key)->first();
        if (!$setting) {
            throw new SettingNotFoundException($key);
        }

        $setting->update([
            'value' => $this->getProperty($properties, SettingsProperty::ENCRYPTED->value) ? encrypt($value) : $value,
            'properties' => json_encode($properties),
        ]);

        return $setting;
    }

    public function delete(string $key): bool
    {
        return Setting::where('key', $key)->delete();
    }
}

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

    /**
     * @throws SettingNotFoundException
     */
    public function set(string $key, mixed $value = null, bool $updateIfExists = false, array $properties = [SettingsProperty::INTERNAL->value => true]): Setting
    {
        $setting = Setting::where('key', $key)->first();

        if (!$this->getProperty($properties, SettingsProperty::TYPE->value)) {
            $properties = array_merge($properties, ['type' => $this->detectType($value)]);
        }

        if ($updateIfExists) {
            if (!$setting) {
                throw new SettingNotFoundException($key);
            }

            $setting->update([
                'value' => $this->getProperty($properties, SettingsProperty::ENCRYPTED->value) ? encrypt($value) : $value,
                'properties' => json_encode($properties),
            ]);
        }

        if (!$setting) {
            $setting = Setting::create([
                'key' => $key,
                'value' => $this->getProperty($properties, SettingsProperty::ENCRYPTED->value) ? encrypt($value) : $value,
                'properties' => json_encode($properties),
            ]);
        }

        return $setting;
    }

    public function detectType(mixed $value): mixed
    {
        return match (true) {
            is_int($value) => 'int',
            is_float($value) => 'float',
            is_bool($value) => 'bool',
            is_array($value) => 'array',
            default => 'string',
        };
    }

    /**
     * @throws SettingNotFoundException
     */
    public function update(string $key, mixed $value = null, array $properties = [SettingsProperty::INTERNAL->value => true]): Setting
    {
        $setting = Setting::where('key', $key)->first();
        if (!$setting) {
            throw new SettingNotFoundException($key);
        }

        if (!$this->getProperty($properties, SettingsProperty::TYPE->value)) {
            $properties = array_merge($properties, ['type' => $this->detectType($value)]);
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

    private function convertTypes(mixed $value, ?string $type): mixed
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
        return $properties[$property] ?? null;
    }
}

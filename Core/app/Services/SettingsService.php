<?php

declare(strict_types=1);

namespace Modules\Core\Services;

use Exception;
use Modules\Core\Enums\SettingsProperty;
use Modules\Core\Exceptions\SettingNotFoundException;
use Modules\Core\Models\Setting;

class SettingsService
{
    public function get(string $key, mixed $default = null, ?Setting $setting = null): mixed
    {
        if (!$setting) {
            $setting = Setting::where('key', $key)->first();
            if (!$setting) {
                return $default;
            }
        }

        $encrypted = $setting->hasProperty(SettingsProperty::ENCRYPTED);

        if ($encrypted) {
            try {
                $value = decrypt($setting->value);
            } catch (Exception) {
                $value = $setting->value;
            }
        } else {
            $value = $setting->value;
        }

        return $this->convertTypes($value, $setting->getProperty(SettingsProperty::TYPE));
    }

    /**
     * @throws SettingNotFoundException
     */
    public function set(string $key, mixed $value = null, bool $updateIfExists = false, ?array $properties = null): Setting
    {
        $setting = Setting::where('key', $key)->first();

        if ($updateIfExists && $setting) {
            $properties = $this->mergeProperties($setting, $properties);
            if (!$this->getProperty($properties, SettingsProperty::TYPE)) {
                $properties['type'] = $this->detectType($value);
            }

            $setting->update([
                'value' => $this->getProperty($properties, SettingsProperty::ENCRYPTED) ? encrypt($value) : $value,
                'properties' => json_encode($properties),
            ]);
        }

        if (!$setting) {
            $properties ??= [SettingsProperty::INTERNAL => true];
            if (!$this->getProperty($properties, SettingsProperty::TYPE)) {
                $properties['type'] = $this->detectType($value);
            }

            $setting = Setting::create([
                'key' => $key,
                'value' => $this->getProperty($properties, SettingsProperty::ENCRYPTED) ? encrypt($value) : $value,
                'properties' => json_encode($properties),
            ]);
        }

        return $setting;
    }

    public function detectType(mixed $value): string
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
    public function update(string $key, mixed $value = null, ?array $properties = null): Setting
    {
        $setting = Setting::where('key', $key)->first();
        if (!$setting) {
            throw new SettingNotFoundException($key);
        }

        $properties = $this->mergeProperties($setting, $properties);
        if (!$this->getProperty($properties, SettingsProperty::TYPE)) {
            $properties['type'] = $this->detectType($value);
        }

        $setting->update([
            'value' => $this->getProperty($properties, SettingsProperty::ENCRYPTED) ? encrypt($value) : $value,
            'properties' => json_encode($properties),
        ]);

        return $setting;
    }

    public function delete(string $key): bool|null|int
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

    private function mergeProperties(Setting $setting, ?array $properties): array
    {
        $existingProperties = $setting->properties;
        if (is_string($existingProperties)) {
            $existingProperties = json_decode($existingProperties, true) ?? [];
        }
        if (!is_array($existingProperties)) {
            $existingProperties = [];
        }

        return array_merge($existingProperties, $properties ?? []);
    }
}

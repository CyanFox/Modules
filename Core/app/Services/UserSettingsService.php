<?php

declare(strict_types=1);

namespace Modules\Core\Services;

use Exception;
use Modules\Core\Enums\SettingsProperty;
use Modules\Core\Exceptions\SettingNotFoundException;
use Modules\Core\Models\UserSetting;

class UserSettingsService
{
    public function get(int $userId, string $key, mixed $default = null): mixed
    {
        $setting = UserSetting::where(['user_id' => $userId, 'key' => $key])->first();
        if (!$setting) {
            return $default;
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

    /**
     * @throws SettingNotFoundException
     */
    public function set(int $userId, string $key, mixed $value = null, bool $updateIfExists = false, ?array $properties = null): UserSetting
    {
        $setting = UserSetting::where(['user_id' => $userId, 'key' => $key])->first();

        if ($updateIfExists && $setting) {
            $properties = $this->mergeProperties($setting, $properties);
            if (!$this->getProperty($properties, SettingsProperty::TYPE)) {
                $properties['type'] = $this->detectType($value);
            }

            $setting->update([
                'user_id' => $userId,
                'value' => $this->getProperty($properties, SettingsProperty::ENCRYPTED) ? encrypt($value) : $value,
                'properties' => json_encode($properties),
            ]);
        }

        if (!$setting) {
            $properties ??= [SettingsProperty::INTERNAL => false];
            if (!$this->getProperty($properties, SettingsProperty::TYPE)) {
                $properties['type'] = $this->detectType($value);
            }

            $setting = UserSetting::create([
                'user_id' => $userId,
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
    public function update(int $userId, string $key, mixed $value = null, ?array $properties = null): UserSetting
    {
        $setting = UserSetting::where(['user_id' => $userId, 'key' => $key])->first();
        if (!$setting) {
            throw new SettingNotFoundException($key);
        }

        $properties = $this->mergeProperties($setting, $properties);
        if (!$this->getProperty($properties, SettingsProperty::TYPE)) {
            $properties['type'] = $this->detectType($value);
        }

        $setting->update([
            'user_id' => $userId,
            'value' => $this->getProperty($properties, SettingsProperty::ENCRYPTED) ? encrypt($value) : $value,
            'properties' => json_encode($properties),
        ]);

        return $setting;
    }

    public function delete(int $userId, string $key): bool|null|int
    {
        return UserSetting::where(['user_id' => $userId, 'key' => $key])->delete();
    }

    private function mergeProperties(UserSetting $setting, ?array $properties): array
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

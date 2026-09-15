<?php

declare(strict_types=1);

namespace Modules\Core\Services;

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
    public function set(int $userId, string $key, string $value, bool $updateIfExists = false, array $properties = [SettingsProperty::INTERNAL->value => false]): UserSetting
    {
        $setting = UserSetting::where(['user_id' => $userId, 'key' => $key])->first();
        if (!$setting) {
            throw new SettingNotFoundException($key);
        }

        if ($updateIfExists) {
            $setting->update([
                'user_id' => $userId,
                'value' => $this->getProperty($properties, SettingsProperty::ENCRYPTED->value) ? encrypt($value) : $value,
                'properties' => json_encode($properties),
            ]);
        }

        if (blank($setting)) {
            $setting = UserSetting::create([
                'user_id' => $userId,
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
    public function update(int $userId, string $key, string $value, array $properties = [SettingsProperty::INTERNAL->value => false]): UserSetting
    {
        $setting = UserSetting::where(['user_id' => $userId, 'key' => $key])->first();
        if (!$setting) {
            throw new SettingNotFoundException($key);
        }

        $setting->update([
            'user_id' => $userId,
            'value' => $this->getProperty($properties, SettingsProperty::ENCRYPTED->value) ? encrypt($value) : $value,
            'properties' => json_encode($properties),
        ]);

        return $setting;
    }

    public function delete(int $userId, string $key): bool
    {
        return UserSetting::where(['user_id' => $userId, 'key' => $key])->delete();
    }
}

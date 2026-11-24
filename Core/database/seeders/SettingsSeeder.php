<?php

declare(strict_types=1);

namespace Modules\Core\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Core\Enums\SettingsProperty;
use Modules\Core\Exceptions\SettingNotFoundException;
use Modules\Core\Facades\Settings;

class SettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @throws SettingNotFoundException
     */
    public function run(): void
    {
        $settings = [
            ['key' => 'app.name', 'value' => config('app.name'), 'properties' => [SettingsProperty::INTERNAL => false, SettingsProperty::LANG_KEY => 'core::settings.app.name']],
            ['key' => 'app.locale', 'value' => config('app.locale'), 'properties' => [SettingsProperty::INTERNAL => false, SettingsProperty::LANG_KEY => 'core::settings.app.locale']],
            ['key' => 'app.url', 'value' => config('app.url'), 'properties' => [SettingsProperty::INTERNAL => false, SettingsProperty::LANG_KEY => 'core::settings.app.url', SettingsProperty::RULES => 'url']],
            ['key' => 'app.timezone', 'value' => config('app.timezone'), 'properties' => [SettingsProperty::INTERNAL => false, SettingsProperty::LANG_KEY => 'core::settings.app.timezone', SettingsProperty::RULES => 'timezone']],
            ['key' => 'app.date_format', 'value' => 'Y-m-d', 'properties' => [SettingsProperty::INTERNAL => false, SettingsProperty::LANG_KEY => 'core::settings.app.date_format']],
            ['key' => 'app.time_format', 'value' => 'H:i', 'properties' => [SettingsProperty::INTERNAL => false, SettingsProperty::LANG_KEY => 'core::settings.app.time_format']],
            ['key' => 'app.logo', 'value' => '/img/Logo.svg', 'properties' => [SettingsProperty::INTERNAL => false, SettingsProperty::LANG_KEY => 'core::settings.app.logo']],
            ['key' => 'app.force_https', 'value' => false, 'properties' => [SettingsProperty::INTERNAL => false, SettingsProperty::LANG_KEY => 'core::settings.app.force_https']],

            ['key' => 'core.default_avatar_url', 'value' => 'https://avatars.cyanfox.de/beam/100/{email_md5}', 'properties' => [SettingsProperty::INTERNAL => false, SettingsProperty::LANG_KEY => 'core::settings.core.default_avatar_url']],
        ];

        foreach ($settings as $setting) {
            Settings::set($setting['key'], $setting['value'], properties: array_merge($setting['properties'], [SettingsProperty::MODULE => 'Core']));
        }
    }
}

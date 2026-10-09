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
            ['key' => 'app.name', 'value' => config('app.name'), 'properties' => [SettingsProperty::INTERNAL => false]],
            ['key' => 'app.locale', 'value' => config('app.locale'), 'properties' => [SettingsProperty::INTERNAL => false]],
            ['key' => 'app.url', 'value' => config('app.url'), 'properties' => [SettingsProperty::INTERNAL => false]],
            ['key' => 'app.date_format', 'value' => 'Y-m-d', 'properties' => [SettingsProperty::INTERNAL => false]],
            ['key' => 'app.time_format', 'value' => 'H:i', 'properties' => [SettingsProperty::INTERNAL => false]],
            ['key' => 'app.logo', 'value' => '/img/Logo.svg', 'properties' => [SettingsProperty::INTERNAL => false]],
            ['key' => 'app.force_https', 'value' => false, 'properties' => [SettingsProperty::INTERNAL => false]],

            ['key' => 'core.default_avatar_url', 'value' => 'https://avatars.cyanfox.de/beam/100/{email_hash}', 'properties' => [SettingsProperty::INTERNAL => false]],
        ];

        foreach ($settings as $setting) {
            Settings::set($setting['key'], $setting['value'], properties: array_merge($setting['properties'], [SettingsProperty::MODULE => 'Core']));
        }
    }
}

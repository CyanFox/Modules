<?php

declare(strict_types=1);

namespace Modules\Admin\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Core\Enums\SettingsProperty;
use Modules\Core\Facades\Settings;

class SettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            ['key' => 'admin.logo.css', 'value' => config('admin.logo.css'), 'properties' => [SettingsProperty::INTERNAL => false, SettingsProperty::LANG_KEY => 'admin::settings.keys.logo.css', SettingsProperty::PERMISSIONS => ['admin.dashboard']]],
            ['key' => 'admin.logo.show_app_name', 'value' => config('admin.logo.show_app_name'), 'properties' => [SettingsProperty::INTERNAL => false, SettingsProperty::LANG_KEY => 'admin::settings.keys.logo.show_app_name', SettingsProperty::PERMISSIONS => ['admin.dashboard']]],
            ['key' => 'admin.logo.show_logo', 'value' => config('admin.logo.show_logo'), 'properties' => [SettingsProperty::INTERNAL => false, SettingsProperty::LANG_KEY => 'admin::settings.keys.logo.show_logo', SettingsProperty::PERMISSIONS => ['admin.dashboard']]],
        ];

        foreach ($settings as $setting) {
            Settings::set($setting['key'], $setting['value'], properties: array_merge($setting['properties'], [SettingsProperty::MODULE => 'Admin']));
        }
    }
}

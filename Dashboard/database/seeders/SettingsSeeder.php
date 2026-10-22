<?php

declare(strict_types=1);

namespace Modules\Dashboard\Database\Seeders;

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
            ['key' => 'dashboard.logo.css', 'value' => config('dashboard.logo.css'), 'properties' => [SettingsProperty::INTERNAL => false, SettingsProperty::LANG_KEY => 'dashboard::settings.logo.css']],
            ['key' => 'dashboard.logo.show_app_name', 'value' => config('dashboard.logo.show_app_name'), 'properties' => [SettingsProperty::INTERNAL => false, SettingsProperty::LANG_KEY => 'dashboard::settings.logo.show_app_name']],
            ['key' => 'dashboard.logo.show_logo', 'value' => config('dashboard.logo.show_logo'), 'properties' => [SettingsProperty::INTERNAL => false, SettingsProperty::LANG_KEY => 'dashboard::settings.logo.show_logo']],
        ];

        foreach ($settings as $setting) {
            Settings::set($setting['key'], $setting['value'], properties: array_merge($setting['properties'], [SettingsProperty::MODULE => 'Dashboard']));
        }
    }
}

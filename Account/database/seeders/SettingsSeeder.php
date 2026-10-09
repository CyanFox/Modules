<?php

namespace Modules\Account\Database\Seeders;

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
            ['key' => 'account.enable.delete_account', 'value' => config('account.enable.delete_account'), 'properties' => [SettingsProperty::INTERNAL => false, SettingsProperty::AUTH => true]],
            ['key' => 'account.enable.change_avatar', 'value' => config('account.enable.change_avatar'), 'properties' => [SettingsProperty::INTERNAL => false, SettingsProperty::AUTH => true]],
        ];

        foreach ($settings as $setting) {
            Settings::set($setting['key'], $setting['value'], properties: array_merge($setting['properties'], [SettingsProperty::MODULE => 'Account']));
        }
    }
}

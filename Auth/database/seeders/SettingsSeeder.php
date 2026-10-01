<?php

declare(strict_types=1);

namespace Modules\Auth\Database\Seeders;

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
            ['key' => 'auth.unsplash.api_key', 'value' => config('auth.unsplash.api_key'), 'properties' => [SettingsProperty::INTERNAL->value => true, SettingsProperty::ENCRYPTED->value => true]],
            ['key' => 'auth.unsplash.query', 'value' => config('auth.unsplash.query'), 'properties' => [SettingsProperty::INTERNAL->value => true]],
            ['key' => 'auth.unsplash.utm', 'value' => config('auth.unsplash.utm'), 'properties' => [SettingsProperty::INTERNAL->value => false]],
            ['key' => 'auth.unsplash.fallback_css', 'value' => config('auth.unsplash.fallback_css'), 'properties' => [SettingsProperty::INTERNAL->value => false]],

            ['key' => 'auth.login.enabled', 'value' => config('auth.login.enabled'), 'properties' => [SettingsProperty::INTERNAL->value => false]],
            ['key' => 'auth.login.captcha', 'value' => config('auth.login.captcha'), 'properties' => [SettingsProperty::INTERNAL->value => false]],
            ['key' => 'auth.login.rate_limit', 'value' => config('auth.login.rate_limit'), 'properties' => [SettingsProperty::INTERNAL->value => true]],
            ['key' => 'auth.login.redirect', 'value' => config('auth.login.redirect'), 'properties' => [SettingsProperty::INTERNAL->value => false]],

            ['key' => 'auth.register.enabled', 'value' => config('auth.register.enabled'), 'properties' => [SettingsProperty::INTERNAL->value => false]],
            ['key' => 'auth.register.captcha', 'value' => config('auth.register.captcha'), 'properties' => [SettingsProperty::INTERNAL->value => false]],
            ['key' => 'auth.register.rate_limit', 'value' => config('auth.register.rate_limit'), 'properties' => [SettingsProperty::INTERNAL->value => true]],

            ['key' => 'auth.forgot_password.enabled', 'value' => config('auth.forgot_password.enabled'), 'properties' => [SettingsProperty::INTERNAL->value => false]],
            ['key' => 'auth.forgot_password.captcha', 'value' => config('auth.forgot_password.captcha'), 'properties' => [SettingsProperty::INTERNAL->value => false]],
            ['key' => 'auth.forgot_password.rate_limit', 'value' => config('auth.forgot_password.rate_limit'), 'properties' => [SettingsProperty::INTERNAL->value => true]],

            ['key' => 'auth.profile.layout', 'value' => config('auth.profile.layout'), 'properties' => [SettingsProperty::INTERNAL->value => false, SettingsProperty::AUTH->value => true]],
            ['key' => 'auth.profile.enable.change_avatar', 'value' => config('auth.profile.enable.change_avatar'), 'properties' => [SettingsProperty::INTERNAL->value => false, SettingsProperty::AUTH->value => true]],
            ['key' => 'auth.profile.enable.delete_account', 'value' => config('auth.profile.enable.delete_account'), 'properties' => [SettingsProperty::INTERNAL->value => false, SettingsProperty::AUTH->value => true]],

            ['key' => 'auth.password.minimum_length', 'value' => config('auth.password.minimum_length'), 'properties' => [SettingsProperty::INTERNAL->value => false]],
            ['key' => 'auth.password.require.numbers', 'value' => config('auth.password.require.numbers'), 'properties' => [SettingsProperty::INTERNAL->value => false]],
            ['key' => 'auth.password.require.special_characters', 'value' => config('auth.password.require.special_characters'), 'properties' => [SettingsProperty::INTERNAL->value => false]],
            ['key' => 'auth.password.require.uppercase_letters', 'value' => config('auth.password.require.uppercase_letters'), 'properties' => [SettingsProperty::INTERNAL->value => false]],
            ['key' => 'auth.password.require.lowercase_letters', 'value' => config('auth.password.require.lowercase_letters'), 'properties' => [SettingsProperty::INTERNAL->value => false]],
            ['key' => 'auth.password.require.uncompromised', 'value' => config('auth.password.require.uncompromised'), 'properties' => [SettingsProperty::INTERNAL->value => false]],
        ];

        foreach ($settings as $setting) {
            Settings::set($setting['key'], $setting['value'], properties: array_merge($setting['properties'], [SettingsProperty::MODULE->value => 'Auth']));
        }
    }
}

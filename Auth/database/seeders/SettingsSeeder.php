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
            ['key' => 'auth.unsplash.api_key', 'value' => config('auth.unsplash.api_key'), 'properties' => [SettingsProperty::INTERNAL => true, SettingsProperty::ENCRYPTED => true, SettingsProperty::LANG_KEY => 'auth::settings.unsplash.api_key']],
            ['key' => 'auth.unsplash.query', 'value' => config('auth.unsplash.query'), 'properties' => [SettingsProperty::INTERNAL => true, SettingsProperty::LANG_KEY => 'auth::settings.unsplash.query']],
            ['key' => 'auth.unsplash.utm', 'value' => config('auth.unsplash.utm'), 'properties' => [SettingsProperty::INTERNAL => false, SettingsProperty::LANG_KEY => 'auth::settings.unsplash.utm']],
            ['key' => 'auth.unsplash.fallback_css', 'value' => config('auth.unsplash.fallback_css'), 'properties' => [SettingsProperty::INTERNAL => false, SettingsProperty::LANG_KEY => 'auth::settings.unsplash.fallback_css']],

            ['key' => 'auth.login.enabled', 'value' => config('auth.login.enabled'), 'properties' => [SettingsProperty::INTERNAL => false, SettingsProperty::LANG_KEY => 'auth::settings.login.enabled']],
            ['key' => 'auth.login.captcha', 'value' => config('auth.login.captcha'), 'properties' => [SettingsProperty::INTERNAL => false, SettingsProperty::LANG_KEY => 'auth::settings.login.captcha']],
            ['key' => 'auth.login.rate_limit', 'value' => config('auth.login.rate_limit'), 'properties' => [SettingsProperty::INTERNAL => true, SettingsProperty::LANG_KEY => 'auth::settings.login.rate_limit']],
            ['key' => 'auth.login.redirect', 'value' => config('auth.login.redirect'), 'properties' => [SettingsProperty::INTERNAL => false, SettingsProperty::LANG_KEY => 'auth::settings.login.redirect']],

            ['key' => 'auth.register.enabled', 'value' => config('auth.register.enabled'), 'properties' => [SettingsProperty::INTERNAL => false, SettingsProperty::LANG_KEY => 'auth::settings.register.enabled']],
            ['key' => 'auth.register.captcha', 'value' => config('auth.register.captcha'), 'properties' => [SettingsProperty::INTERNAL => false, SettingsProperty::LANG_KEY => 'auth::settings.register.captcha']],
            ['key' => 'auth.register.rate_limit', 'value' => config('auth.register.rate_limit'), 'properties' => [SettingsProperty::INTERNAL => true, SettingsProperty::LANG_KEY => 'auth::settings.register.rate_limit']],

            ['key' => 'auth.forgot_password.enabled', 'value' => config('auth.forgot_password.enabled'), 'properties' => [SettingsProperty::INTERNAL => false, SettingsProperty::LANG_KEY => 'auth::settings.forgot_password.enabled']],
            ['key' => 'auth.forgot_password.captcha', 'value' => config('auth.forgot_password.captcha'), 'properties' => [SettingsProperty::INTERNAL => false, SettingsProperty::LANG_KEY => 'auth::settings.forgot_password.captcha']],
            ['key' => 'auth.forgot_password.rate_limit', 'value' => config('auth.forgot_password.rate_limit'), 'properties' => [SettingsProperty::INTERNAL => true, SettingsProperty::LANG_KEY => 'auth::settings.forgot_password.rate_limit']],

            ['key' => 'auth.profile.layout', 'value' => config('auth.profile.layout'), 'properties' => [SettingsProperty::INTERNAL => false, SettingsProperty::AUTH => true, SettingsProperty::LANG_KEY => 'auth::settings.profile.layout']],

            ['key' => 'auth.password.minimum_length', 'value' => config('auth.password.minimum_length'), 'properties' => [SettingsProperty::INTERNAL => false, SettingsProperty::LANG_KEY => 'auth::settings.password.minimum_length']],
            ['key' => 'auth.password.require.numbers', 'value' => config('auth.password.require.numbers'), 'properties' => [SettingsProperty::INTERNAL => false, SettingsProperty::LANG_KEY => 'auth::settings.password.require.numbers']],
            ['key' => 'auth.password.require.special_characters', 'value' => config('auth.password.require.special_characters'), 'properties' => [SettingsProperty::INTERNAL => false, SettingsProperty::LANG_KEY => 'auth::settings.password.require.special_characters']],
            ['key' => 'auth.password.require.uppercase_letters', 'value' => config('auth.password.require.uppercase_letters'), 'properties' => [SettingsProperty::INTERNAL => false, SettingsProperty::LANG_KEY => 'auth::settings.password.require.uppercase_letters']],
            ['key' => 'auth.password.require.lowercase_letters', 'value' => config('auth.password.require.lowercase_letters'), 'properties' => [SettingsProperty::INTERNAL => false, SettingsProperty::LANG_KEY => 'auth::settings.password.require.lowercase_letters']],
            ['key' => 'auth.password.require.uncompromised', 'value' => config('auth.password.require.uncompromised'), 'properties' => [SettingsProperty::INTERNAL => false, SettingsProperty::LANG_KEY => 'auth::settings.password.require.uncompromised']],

            ['key' => 'auth.logo.css', 'value' => 'width: 70px', 'properties' => [SettingsProperty::INTERNAL => false, SettingsProperty::LANG_KEY => 'auth::settings.logo.css']],
        ];

        foreach ($settings as $setting) {
            Settings::set($setting['key'], $setting['value'], properties: array_merge($setting['properties'], [SettingsProperty::MODULE => 'Auth']));
        }
    }
}

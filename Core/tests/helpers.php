<?php

declare(strict_types=1);

use Modules\Core\Enums\SettingsProperty;
use Modules\Core\Facades\Settings;

/**
 * Create (or update) a setting, so components that depend on settings work in tests.
 */
function setSetting(string $key, mixed $value, array $properties = []): void
{
    Settings::set($key, $value, true, array_merge([SettingsProperty::INTERNAL => true], $properties));
}

/**
 * Seed the minimal core settings used by layouts and components.
 */
function seedCoreSettings(): void
{
    setSetting('app.name', config('app.name'));
    setSetting('app.url', config('app.url'), [SettingsProperty::RULES => 'url']);
    setSetting('app.timezone', config('app.timezone'), [SettingsProperty::RULES => 'timezone']);
    setSetting('app.locale', config('app.locale'));
    setSetting('app.date_format', 'Y-m-d');
    setSetting('app.time_format', 'H:i');
    setSetting('app.logo', '/img/Logo.svg');
    setSetting('core.default_avatar_url', 'https://avatars.example.com/beam/100/{email_md5}');
}

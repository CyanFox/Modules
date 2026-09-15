<?php

declare(strict_types=1);

use Modules\Core\Services\SettingsService;
use Modules\Core\Services\UserSettingsService;

if (!function_exists('settings')) {
    function settings(?string $key, mixed $default = null): mixed
    {
        if (!$key) {
            return app(SettingsService::class);
        }

        return app(SettingsService::class)->get($key, $default);
    }
}

if (!function_exists('userSettings')) {
    function userSettings(?string $key, ?int $userId, mixed $default = null): mixed
    {
        if (!$key) {
            return app(UserSettingsService::class);
        }

        if (!$userId) {
            $userId = auth()->id();
        }

        return app(UserSettingsService::class)->get($userId, $key, $default);
    }
}

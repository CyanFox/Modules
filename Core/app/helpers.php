<?php

declare(strict_types=1);

use Illuminate\Support\Carbon;
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

if (!function_exists('formatDateTime')) {
    function formatDateTime($date, $format = null): string
    {
        if (blank($date)) {
            return '';
        }

        if ($format) {
            return Carbon::parse($date)->format($format);
        }

        return Carbon::parse($date)->format(settings('app.date_format', 'Y-m-d') . ' ' . settings('app.time_format', 'H:i'));
    }
}

if (!function_exists('formatDate')) {
    function formatDate($date, $format = null): string
    {
        if (blank($date)) {
            return '';
        }

        if ($format) {
            return Carbon::parse($date)->format($format);
        }

        return Carbon::parse($date)->format(settings('app.date_format', 'Y-m-d'));
    }
}

if (!function_exists('formatTime')) {
    function formatTime(string $time, $format = null): string
    {
        if (blank($time)) {
            return '';
        }

        if ($format) {
            return Carbon::parse($time)->format($format);
        }

        return Carbon::parse($time)->format(settings('app.time_format', 'H:i'));
    }
}

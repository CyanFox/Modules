<?php

declare(strict_types=1);

namespace Modules\Core\Facades;

use Illuminate\Support\Facades\Facade;
use Modules\Core\Services\UserSettingsService;

class UserSettings extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return UserSettingsService::class;
    }
}

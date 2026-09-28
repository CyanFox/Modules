<?php

declare(strict_types=1);

namespace Modules\Core\Facades;

use Illuminate\Support\Facades\Facade;
use Modules\Core\Services\SettingsService;

class Settings extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return SettingsService::class;
    }
}

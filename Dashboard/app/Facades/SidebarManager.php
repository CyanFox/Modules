<?php

namespace Modules\Dashboard\Facades;


use Illuminate\Support\Facades\Facade;
use Modules\Dashboard\Services\SidebarService;

/**
 * @method static void add(string $label, string $icon, string $route = 'dashboard', string|null $link = null, bool $isExternal = false)
 * @method static void get(string|null $label = null)
 * @method static array getAll()
 *
 * @see \Modules\Dashboard\Services\SidebarService
 */
class SidebarManager extends Facade
{
    protected static function getFacadeAccessor()
    {
        return SidebarService::class;
    }
}

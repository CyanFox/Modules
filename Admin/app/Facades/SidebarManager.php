<?php

namespace Modules\Admin\Facades;


use Illuminate\Support\Facades\Facade;
use Modules\Admin\Services\SidebarService;

/**
 * @method static void add(string $label, string $icon, string $link, bool $isExternal = false)
 * @method static void get(string|null $label = null)
 * @method static array getAll()
 *
 * @see \Modules\Admin\Services\SidebarService
 */
class SidebarManager extends Facade
{
    protected static function getFacadeAccessor()
    {
        return SidebarService::class;
    }
}

<?php

namespace Modules\Auth\Facades;

use Illuminate\Support\Facades\Facade;
use Modules\Auth\Services\UnsplashService;

/**
 * @method static array returnBackground()
 * @method static string|null getUTM()
 * @method static array|null getRandomUnsplashImage(void $cache = true)
 *
 * @see \Modules\Auth\Services\UnsplashService
 */
class UnsplashManager extends Facade
{
    protected static function getFacadeAccessor()
    {
        return UnsplashService::class;
    }
}

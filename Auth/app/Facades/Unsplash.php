<?php

namespace Modules\Auth\Facades;

use Illuminate\Support\Facades\Facade;
use Modules\Auth\Services\UnsplashService;

class Unsplash extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return UnsplashService::class;
    }
}

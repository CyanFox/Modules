<?php

use Modules\Auth\Services\UnsplashService;

if (! function_exists('unsplash')) {
    function unsplash(): UnsplashService
    {
        return new UnsplashService;
    }
}

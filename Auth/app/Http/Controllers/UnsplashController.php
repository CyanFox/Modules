<?php

namespace Modules\Auth\Http\Controllers;

use Dedoc\Scramble\Attributes\Group;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Modules\Auth\Facades\UnsplashManager;

#[Group('Unsplash')]
class UnsplashController
{
    public function getRandomBackgroundCss(Request $request)
    {
        return apiResponse('success', [
            UnsplashManager::returnBackground()
        ]);
    }

    public function getRandomBackgroundUrl()
    {
        return apiResponse('success', [
            UnsplashManager::getRandomUnsplashImage()
        ]);
    }

    public function getUtmSource()
    {
        return apiResponse('success', [
            UnsplashManager::getUTM()
        ]);
    }
}

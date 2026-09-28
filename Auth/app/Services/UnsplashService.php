<?php

namespace Modules\Auth\Services;

use Exception;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class UnsplashService
{
    public function returnBackground(): array
    {
        try {
            $imageData = self::getRandomUnsplashImage();
            $utmSource = self::getUTM();

            if ($imageData === null) {
                throw new Exception('Unsplash API key is not set.');
            }

            $imagePath = $imageData[0]['urls']['regular'];
            $photoLink = $imageData[0]['links']['html'] . $utmSource;
            $authorName = $imageData[0]['user']['name'];
            $authorLink = $imageData[0]['user']['links']['html'] . $utmSource;
            $error = null;

            $css = "background-image: url('" . $imagePath . "');
                    background-size: cover;
                    background-repeat: no-repeat;
                    background-position: center;
                    background-attachment: fixed;
                    background-color: #000000;
                    filter: blur(5px);
                    opacity: 0.5";
        } catch (Exception $e) {
            $css = settings('auth.unsplash.fallback_css', config('auth.unsplash.fallback_css'));

            $error = $e->getMessage();
            $photoLink = null;
            $authorName = null;
            $authorLink = null;
        }

        return [
            'css' => $css,
            'error' => $error,
            'photo' => $photoLink,
            'author' => $authorName,
            'authorURL' => $authorLink,
            'utm' => 'https://unsplash.com/' . $this->getUTM(),
        ];
    }

    public function getRandomUnsplashImage($cache = true): ?array
    {
        if (!$cache) {
            Cache::forget('unsplash_image');
        }

        $apiKey = settings('auth.unsplash.api_key', config('auth.unsplash.api_key'));
        if (blank($apiKey)) {
            return null;
        }

        return Cache::remember('unsplash_image', now()->addHour(), function () use ($apiKey) {
            return Http::withHeader('Authorization', "Client-ID " . $apiKey)
                ->get('https://api.unsplash.com/photos/random?count=1&query=' .
                    settings('auth.unsplash.query', config('auth.unsplash.query')))
                ->json();
        });
    }

    public function getUTM(): ?string
    {
        return settings('auth.unsplash.utm', config('auth.unsplash.utm'));
    }
}

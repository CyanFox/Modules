<?php

declare(strict_types=1);

return [
    'api_key' => env('UNSPLASH_API_KEY'),
    'utm' => env('UNSPLASH_UTM', '?utm_source=APP_NAME&utm_medium=referral'),
    'fallback_css' => env('UNSPLASH_FALLBACK_CSS',
        'background: #3D3846; background: radial-gradient(circle,rgba(61, 56, 70, 1) 0%, rgba(36, 31, 49, 1) 100%);'),
    'query' => env('UNSPLASH_QUERY', 'beautiful,landscape'),
];

<?php

return [
    'api_key' => env('UNSPLASH_API_KEY'),
    'utm' => env('UNSPLASH_UTM', '?utm_source=APP_NAME&utm_medium=referral'),
    'fallback_css' => env('UNSPLASH_FALLBACK_CSS',
        'background: rgb(94,0,90); background: linear-gradient(145deg, rgba(94,0,90,1) 0%, rgba(0,166,255,1) 100%);'),
    'query' => env('UNSPLASH_QUERY', 'beautiful,landscape'),
];

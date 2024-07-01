<?php

return [
    'authentik' => [
        'enable' => env('OAUTHMODULE_AUTENTIK_ENABLE', false),
        'base_url' => env('OAUTHMODULE_AUTENTIK_URL', ''),
        'client_id' => env('OAUTHMODULE_AUTENTIK_CLIENT_ID', ''),
        'client_secret' => env('OAUTHMODULE_AUTENTIK_CLIENT_SECRET', ''),
        'redirect' => env('OAUTHMODULE_AUTENTIK_REDIRECT', ''),
    ],

    'github' => [
        'enable' => env('OAUTHMODULE_GITHUB_ENABLE', false),
        'client_id' => env('OAUTHMODULE_GITHUB_CLIENT_ID', ''),
        'client_secret' => env('OAUTHMODULE_GITHUB_CLIENT_SECRET', ''),
        'redirect' => env('OAUTHMODULE_GITHUB_REDIRECT', ''),
    ],

    'google' => [
        'enable' => env('OAUTHMODULE_GOOGLE_ENABLE', false),
        'client_id' => env('OAUTHMODULE_GOOGLE_CLIENT_ID', ''),
        'client_secret' => env('OAUTHMODULE_GOOGLE_CLIENT_SECRET', ''),
        'redirect' => env('OAUTHMODULE_GOOGLE_REDIRECT', ''),
    ],

    'discord' => [
        'enable' => env('OAUTHMODULE_DISCORD_ENABLE', false),
        'client_id' => env('OAUTHMODULE_DISCORD_CLIENT_ID', ''),
        'client_secret' => env('OAUTHMODULE_DISCORD_CLIENT_SECRET', ''),
        'redirect' => env('OAUTHMODULE_DISCORD_REDIRECT', ''),
    ],
];

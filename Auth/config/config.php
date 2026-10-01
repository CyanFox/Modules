<?php

declare(strict_types=1);

return [
    'logo_size' => 'w-20 h-20',
    'default_avatar_url' => 'https://avatars.cyanfox.de/beam/100/{email_md5}',

    'login' => [
        'enabled' => true,
        'captcha' => false,
        'redirect' => null,
        'rate_limit' => 10,
    ],

    'register' => [
        'enabled' => false,
        'captcha' => false,
        'rate_limit' => 5,
    ],

    'forgot_password' => [
        'enabled' => true,
        'captcha' => false,
        'rate_limit' => 10,
    ],

    'profile' => [
        'layout' => 'auth::components.layouts.auth',
        'enable' => [
            'change_avatar' => true,
            'delete_account' => true,
        ],
    ],

    'password' => [
        'minimum_length' => 8,
        'require' => [
            'numbers' => false,
            'special_characters' => false,
            'uppercase_letters' => false,
            'lowercase_letters' => false,
            'uncompromised' => true,
        ],
    ],
];

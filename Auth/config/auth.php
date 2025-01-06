<?php

return [
    'logo_size' => 'w-20 h-20',
    'default_avatar_url' => 'https://avatars.cyanfox.de/beam/100/{email_md5}',

    'login' => [
        'captcha' => false,
        'rate_limit' => 10,
    ],
    'register' => [
        'captcha' => false,
        'rate_limit' => 5,
    ],
    'forgot_password' => [
        'captcha' => false,
        'rate_limit' => 10,
    ],
    'profile' => [
        'layout' => 'auth::components.layouts.auth',
        'enable' => [
            'change_avatar' => true,
            'delete_account' => true,
        ]
    ],
];

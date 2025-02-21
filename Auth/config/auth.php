<?php

return [
    'logo_size' => 'w-20 h-20',
    'default_avatar_url' => 'https://avatars.cyanfox.de/beam/100/{email_md5}',

    'login' => [
        'enable' => true,
        'captcha' => false,
        'rate_limit' => 10,
    ],
    'register' => [
        'enable' => true,
        'captcha' => false,
        'rate_limit' => 5,
    ],
    'forgot_password' => [
        'enable' => true,
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
    'oauth' => [
        'enable' => false,
        'login_text' => 'Login with Authentik',
        'login_color' => 'info',
        'client_id' => '',
        'client_secret' => '',
        'redirect' => 'http://127.0.0.1/oauth/custom/callback',
        'auth_url' => 'http://authentik.test/application/o/authorize/',
        'token_url' => 'http://authentik.test/application/o/token/',
        'user_url' => 'http://authentik.test/application/o/user/',
        'id_field' => 'sub',
        'username_field' => 'preferred_username',
        'email_field' => 'email',
    ],
];

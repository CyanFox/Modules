<?php

return [
    'enable' => [
        'register' => env('AUTHMODULE_ENABLE_REGISTER', true),
        'forgot_password' => env('AUTHMODULE_ENABLE_FORGOT_PASSWORD', true),
        'captcha' => env('AUTHMODULE_ENABLE_CAPTCHA', false),
        'delete_account' => env('AUTHMODULE_ENABLE_DELETE_ACCOUNT', true),
    ],

    'profile' => [
        'default_avatar_url' => env('AUTHMODULE_PROFILE_DEFAULT_AVATAR_URL', 'https://source.boringavatars.com/beam/100/{username}'),
    ],

    'layouts' => [
        'profile' => env('AUTHMODULE_LAYOUTS_PROFILE'),
    ],

    'redirects' =>  [
        'login' => env('AUTHMODULE_REDIRECTS_LOGIN'),
        'register' => env('AUTHMODULE_REDIRECTS_REGISTER'),
    ],

    'password' => [
        'minimum_length' => env('AUTHMODULE_PASSWORD_MINIMUM_LENGTH', 8),
        'blacklist' => env('AUTHMODULE_PASSWORD_BLACKLIST'),
        'require' => [
            'numbers' => env('AUTHMODULE_PASSWORD_REQUIRE_NUMBERS', false),
            'special_characters' => env('AUTHMODULE_PASSWORD_REQUIRE_SPECIAL_CHARACTERS', false),
            'uppercase' => env('AUTHMODULE_PASSWORD_REQUIRE_UPPERCASE', false),
            'lowercase' => env('AUTHMODULE_PASSWORD_REQUIRE_LOWERCASE', false),
        ],
    ],

    'emails' => [
        'forgot_password' => [
            'title' => env('AUTHMODULE_EMAILS_FORGOT_PASSWORD_TITLE', 'Reset Password Notification'),
            'subject' => env('AUTHMODULE_EMAILS_FORGOT_PASSWORD_SUBJECT', 'Password reset for {username}'),
            'content' => env('AUTHMODULE_EMAILS_FORGOT_PASSWORD_CONTENT', 'Hello {username},
You are receiving this email because we received a password reset request for your account.

Click the button below to reset your password:
{resetLink}

If you did not request a password reset, no further action is required.'),
        ],
    ],
];

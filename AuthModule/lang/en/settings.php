<?php

return [
    'tabs' => [
        'auth' => 'Authentication',
        'emails' => 'Emails',
        'account' => 'Account',
    ],

    'enable' => [
        'captcha' => 'Enable Captcha',
        'register' => 'Enable Register',
        'forgot_password' => 'Enable Forgot Password',
    ],

    'security' => [
        'password' => [
            'min_length' => 'Password Min Length',
            'blacklist' => 'Password Blacklist',
            'blacklist_hint' => 'Comma separated list of blacklisted passwords. Example: password123,12345678',
            'require_number' => 'Require Number',
            'require_special_characters' => 'Require Special Characters',
            'require_uppercase' => 'Require Uppercase',
            'require_lowercase' => 'Require Lowercase',
        ],
    ],

    'emails' => [
        'forgot_password' => [
            'title' => 'Forgot Password Email Title',
            'subject' => 'Forgot Password Email Subject',
            'content' => 'Forgot Password Email Content',
            'placeholders' => 'Available Placeholders: {username}, {firstName}, {lastName}, {password}, {loginLink}, {appName}',
        ],
    ],

    'account' => [
        'allow' => [
            'delete_account' => 'Allow Account Deletion',
            'change_avatar' => 'Allow Avatar Change',
        ],
        'default_avatar_url' => 'Default Avatar URL',
        'default_avatar_url_placeholder' => 'Available Placeholders: {username}',
    ],

    'notifications' => [
        'settings_updated' => 'Settings updated successfully.',
    ],
];

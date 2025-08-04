<?php

return [
    'tab_title' => 'Auth Settings',
    'default_avatar_url' => 'Default Avatar URL',
    'default_avatar_url_hint' => 'You can use following placeholders: {email}, {email_md5}, {username}, {first_name}, {last_name}',

    'login_rate_limit' => 'Login Rate Limit',
    'register_rate_limit' => 'Register Rate Limit',

    'unsplash_api_key' => 'Unsplash API Key',
    'unsplash_utm' => 'Unsplash UTM Source',
    'unsplash_fallback_css' => 'Unsplash Fallback CSS',
    'unsplash_query' => 'Unsplash Query',

    'enable_delete_account' => 'Enable account deletion',
    'enable_change_avatar' => 'Enable change avatar',
    'enable_register' => 'Enable user registration',
    'enable_login' => 'Enable user login',
    'enable_forgot_password' => 'Enable forgot password',
    'enable_login_captcha' => 'Enable login captcha',
    'enable_register_captcha' => 'Enable register captcha',
    'enable_forgot_password_captcha' => 'Enable forgot password captcha',

    'enable_oauth' => 'Enable OAuth Login',
    'oauth_well_known_url' => 'OAuth Well-Known URL',
    'oauth_login_color' => 'OAuth Login Button Color',
    'oauth_login_text' => 'OAuth Login Button Text',
    'oauth_id_field' => 'OAuth ID Field',
    'oauth_username_field' => 'OAuth Username Field',
    'oauth_email_field' => 'OAuth Email Field',
    'oauth_client_id' => 'OAuth Client ID',
    'oauth_client_secret' => 'OAuth Client Secret',
    'oauth_redirect_uri' => 'OAuth Redirect URI',
    'oauth_colors' => [
        'primary' => 'Primary',
        'secondary' => 'Secondary',
        'inverse' => 'Inverse',
        'info' => 'Info',
        'warning' => 'Warning',
        'danger' => 'Danger',
        'success' => 'Success',
    ],

    'password_min_length' => 'Minimum Password Length',
    'password_require_uppercase' => 'Require Uppercase Letters',
    'password_require_lowercase' => 'Require Lowercase Letters',
    'password_require_numbers' => 'Require Numbers',
    'password_require_special_characters' => 'Require Special Characters',
    'password_blacklist' => 'Password Blacklist',

    'forgot_password_mail_title' => 'Forgot Password',
    'forgot_password_mail' => [
        'title' => 'Title',
        'subject' => 'Subject',
        'content' => 'Content',
        'hint' => 'You can use the following placeholders: {username}, {firstName}, {lastName}, {resetLink}',
    ],

    'new_session_mail_enabled' => 'Enable New Session Notification',
    'new_session_mail_title' => 'New Session Notification',
    'new_session_mail' => [
        'title' => 'Title',
        'subject' => 'Subject',
        'content' => 'Content',
        'hint' => 'You can use the following placeholders: {username}, {firstName}, {lastName}, {ipAddress}, {userAgent}, {loginTime}',
    ],

    'notifications' => [
        'settings_updated' => 'Settings updated successfully.',
    ],

    'tabs' => [
        'general' => 'General',
        'mail' => 'Mails',
        'oauth' => 'OAuth',
        'password' => 'Password',
    ],
];

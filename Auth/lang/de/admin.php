<?php

return [
    'tab_title' => 'Auth Einstellungen',
    'default_avatar_url' => 'Standard Avatar URL',
    'default_avatar_url_hint' => 'Es stehen folgende Platzhalter zur Verfügung: {email}, {email_md5}, {username}, {first_name}, {last_name}',

    'login_rate_limit' => 'Anmelde Rate Limit',
    'register_rate_limit' => 'Registrierungs Rate Limit',

    'unsplash_api_key' => 'Unsplash API Schlüssel',
    'unsplash_utm' => 'Unsplash UTM Source',
    'unsplash_fallback_css' => 'Unsplash Fallback CSS',
    'unsplash_query' => 'Unsplash Query',

    'enable_delete_account' => 'Accountlöschung aktivieren',
    'enable_change_avatar' => 'Avataränderung aktivieren',
    'enable_register' => 'Registrierung aktivieren',
    'enable_login' => 'Login aktivieren',
    'enable_forgot_password' => 'Passwort vergessen aktivieren',
    'enable_login_captcha' => 'Login Captcha aktivieren',
    'enable_register_captcha' => 'Registrierungs Captcha aktivieren',
    'enable_forgot_password_captcha' => 'Passwort vergessen Captcha aktivieren',

    'enable_oauth' => 'OAuth Login aktivieren',
    'oauth_well_known_url' => 'OAuth Well-Known URL',
    'oauth_login_color' => 'OAuth Login Button Farbe',
    'oauth_login_text' => 'OAuth Login Button Text',
    'oauth_id_field' => 'OAuth ID Feld',
    'oauth_username_field' => 'OAuth Benutzername Feld',
    'oauth_email_field' => 'OAuth E-Mail Feld',
    'oauth_client_id' => 'OAuth Client ID',
    'oauth_client_secret' => 'OAuth Client Secret',
    'oauth_redirect_uri' => 'OAuth Redirect URI',
    'oauth_colors' => [
        'primary' => 'Primary',
        'secondary' => 'Secondary',
        'inverse' => 'Inverse',
        'info' => 'Info',
        'warning' => 'Warnung',
        'danger' => 'Gefahr',
        'success' => 'Erfolg',
    ],

    'password_min_length' => 'Mindest Passwortlänge',
    'password_require_uppercase' => 'Großbuchstaben erforderlich',
    'password_require_lowercase' => 'Kleinbuchstaben erforderlich',
    'password_require_numbers' => 'Zahlen erforderlich',
    'password_require_special_characters' => 'Sonderzeichen erforderlich',
    'password_blacklist' => 'Passwort Blacklist',

    'forgot_password_mail_title' => 'Passwort vergessen',
    'forgot_password_mail' => [
        'title' => 'Titel',
        'subject' => 'Betreff',
        'content' => 'Inhalt',
        'hint' => 'Es stehen folgende Platzhalter zur Verfügung: {username}, {firstName}, {lastName}, {resetLink}',
    ],

    'new_session_mail_title' => 'Neue Sitzungsbenachrichtigung',
    'new_session_mail' => [
        'title' => 'Titel',
        'subject' => 'Betreff',
        'content' => 'Inhalt',
        'hint' => 'Es stehen folgende Platzhalter zur Verfügung: {username}, {firstName}, {lastName}, {ipAddress}, {userAgent}, {loginTime}',
    ],

    'notifications' => [
        'settings_updated' => 'Einstellungen erfolgreich aktualisiert.',
    ],

    'tabs' => [
        'general' => 'Allgemein',
        'mail' => 'E-Mails',
        'oauth' => 'OAuth',
        'password' => 'Passwort',
    ],
];

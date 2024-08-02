<?php

return [
    'tabs' => [
        'auth' => 'Authentifizierung',
        'emails' => 'E-Mails',
        'account' => 'Konto',
    ],

    'enable' => [
        'captcha' => 'Captcha aktivieren',
        'register' => 'Registrierung aktivieren',
        'forgot_password' => 'Passwort vergessen aktivieren',
    ],

    'security' => [
        'password' => [
            'min_length' => 'Mindestlänge des Passworts',
            'blacklist' => 'Passwort-Blacklist',
            'blacklist_hint' => 'Kommagetrennte Liste von gesperrten Passwörtern. Beispiel: password123,12345678,pupsbärchensonderzeichen',
            'require_number' => 'Zahl erforderlich',
            'require_special_characters' => 'Sonderzeichen erforderlich',
            'require_uppercase' => 'Großbuchstaben erforderlich',
            'require_lowercase' => 'Kleinbuchstaben erforderlich',
        ],
    ],

    'emails' => [
        'forgot_password' => [
            'title' => 'Titel der E-Mail "Passwort vergessen"',
            'subject' => 'Betreff der E-Mail "Passwort vergessen"',
            'content' => 'Inhalt der E-Mail "Passwort vergessen"',
            'placeholders' => 'Verfügbare Platzhalter: {username}, {firstName}, {lastName}, {password}, {loginLink}, {appName}',
        ],
    ],

    'account' => [
        'allow' => [
            'delete_account' => 'Konto löschen erlauben',
            'change_avatar' => 'Avatar-Änderung erlauben',
        ],
        'default_avatar_url' => 'Standard-Avatar-URL',
        'default_avatar_url_placeholder' => 'Verfügbare Platzhalter: {username}',
    ],

    'notifications' => [
        'settings_updated' => 'Einstellungen erfolgreich aktualisiert.',
    ],
];

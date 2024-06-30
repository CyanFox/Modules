<?php

return [
    'rate_limit' => 'Zu viele Anmeldeversuche. Bitte versuche es in :seconds Sekunden erneut.',
    'buttons' => [
        'back_to_login' => 'Zurück zum Login',
    ],
    'login' => [
        'tab_title' => 'Login',
        'remember_me' => 'Angemeldet bleiben',
        'two_factor_code' => 'Zwei-Faktor-Code',
        'recovery_code' => 'Wiederherstellungscode',
        'use_two_factor' => 'Zwei-Faktor-Code verwenden',
        'use_recovery_code' => 'Wiederherstellungscode verwenden',
        'user_disabled' => 'Dieser Benutzer wurde deaktiviert.',
        'two_factor_code_invalid' => 'Der Zwei-Faktor-Code ist ungültig.',
        'user_not_found' => 'Wir können keinen Benutzer mit diesem Benutzernamen finden.',

        'buttons' => [
            'login' => 'Login',
            'forgot_password' => 'Passwort vergessen?',
            'register' => 'Registrieren',
        ],
    ],

    'forgot_password' => [
        'tab_title' => 'Passwort vergessen',
        'email_not_found' => 'Wir können keinen Benutzer mit dieser E-Mail-Adresse finden.',
        'buttons' => [
            'send_reset_link' => 'Reset-Link senden',
            'reset_password' => 'Passwort zurücksetzen',
        ],

        'notifications' => [
            'reset_token_expired' => 'Das Reset-Token ist abgelaufen.',
            'reset_token_invalid' => 'Das Reset-Token ist ungültig.',
            'reset_email_sent' => 'Wir haben dir deinen Passwort-Reset-Link per E-Mail geschickt!',
            'password_reset' => 'Dein Passwort wurde zurückgesetzt!',
        ],
    ],

    'register' => [
        'tab_title' => 'Registrieren',
        'username_already_taken' => 'Benutzername bereits vergeben.',
        'email_already_taken' => 'E-Mail-Adresse bereits vergeben.',

        'buttons' => [
            'register' => 'Registrieren',
        ],

        'notifications' => [
            'account_created' => 'Dein Konto wurde erstellt!',
        ],
    ],
];

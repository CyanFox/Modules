<?php

return [
    'tab_title' => 'Profil',

    'tabs' => [
        'overview' => 'Übersicht',
        'sessions' => 'Sitzungen',
        'apiKeys' => 'API-Schlüssel',
    ],

    'notifications' => [
        'profile_updated' => 'Profil erfolgreich aktualisiert.',
        'password_updated' => 'Passwort erfolgreich aktualisiert.',
    ],

    'language_and_theme' => [
        'title' => 'Sprache und Design',
        'language' => 'Sprache',
        'theme' => 'Design',

        'languages' => [
            'en' => 'Englisch',
            'de' => 'Deutsch',
        ],
        'themes' => [
            'light' => 'Hell',
            'dark' => 'Dunkel',
        ],
    ],

    'actions' => [
        'title' => 'Aktionen',

        'buttons' => [
            'activate_two_factor' => '2 Faktor aktivieren',
            'disable_two_factor' => '2 Faktor deaktivieren',
            'regenerate_recovery_codes' => 'Wiederherstellungscodes erneut generieren',
            'delete_account' => 'Konto löschen',
        ],
    ],
    'profile' => [
        'title' => 'Profil',

        'first_name' => 'Vorname',
        'last_name' => 'Nachname',
        'username' => 'Benutzername',
        'email' => 'E-Mail',
    ],

    'password' => [
        'title' => 'Passwort',

        'current_password' => 'Aktuelles Passwort',
        'new_password' => 'Neues Passwort',
        'confirm_password' => 'Passwort bestätigen',
    ],

    'sessions' => [
        'title' => 'Sitzungen',
        'ip_address' => 'IP-Adresse',
        'user_agent' => 'User Agent',
        'platform' => 'Plattform',
        'last_active' => 'Letzte Aktivität',

        'device_types' => [
            'desktop' => 'Desktop',
            'mobile' => 'Mobil',
            'tablet' => 'Tablet',
            'other' => 'Andere',
        ],

        'buttons' => [
            'logout_all' => 'Alle abmelden',
        ],

        'modals' => [
            'logout_all' => [
                'title' => 'Alle abmelden',
                'description' => 'Bist du sicher, dass du alle anderen Sitzungen abmelden möchtest?',
                'confirm' => 'Ja, alle abmelden',
            ],
        ],

        'notifications' => [
            'logged_out' => 'Erfolgreich abgemeldet.',
            'logged_out_all' => 'Erfolgreich aus allen anderen Sitzungen abgemeldet.',
        ],
    ],

    'modals' => [
        'activate_two_fa' => [
            'title' => '2-Faktor-Authentifizierung aktivieren',
            'two_fa_code' => '2-Faktor-Code',
            'invalid_two_factor_code' => 'Der eingegebene 2-Faktor-Code ist ungültig.',

            'notifications' => [
                'two_fa_enabled' => '2-Faktor-Authentifizierung erfolgreich aktiviert.',
            ],
        ],
        'disable_two_fa' => [
            'title' => '2-Faktor-Authentifizierung deaktivieren',
            'description' => 'Bist du sicher, dass du die 2-Faktor-Authentifizierung deaktivieren möchtest?',

            'buttons' => [
                'disable' => 'Deaktivieren',
            ],

            'notifications' => [
                'two_fa_disabled' => '2-Faktor-Authentifizierung erfolgreich deaktiviert.',
            ],
        ],
        'recovery_codes' => [
            'title' => 'Wiederherstellungscodes',
            'description' => 'Speichern Sie diese Wiederherstellungscodes an einem sicheren Ort. Sie können danach nicht mehr angezeigt werden.',

            'buttons' => [
                'regenerate' => 'Neu generieren',
                'download' => 'Herunterladen',
            ],
        ],
        'delete_account' => [
            'title' => 'Konto löschen',
            'description' => 'Bist du sicher, dass du dein Konto löschen möchtest? Alle deine Daten werden dauerhaft gelöscht und können nicht wiederhergestellt werden.',

            'notifications' => [
                'account_deleted' => 'Konto erfolgreich gelöscht.',
            ],
        ],
        'change_avatar' => [
            'title' => 'Avatar ändern',
            'description' => 'Lade ein neues Bild hoch oder gebe eine URL ein, um deinen Avatar zu ändern.',
            'avatar' => 'Avatar',
            'avatar_url' => 'Avatar URL',

            'buttons' => [
                'reset' => 'Zurücksetzen',
            ],

            'notifications' => [
                'avatar_changed' => 'Avatar erfolgreich geändert.',
                'avatar_reset' => 'Avatar erfolgreich zurückgesetzt.',
            ],
        ]
    ],
];

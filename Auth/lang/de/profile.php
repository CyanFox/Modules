<?php

return [
    'tab_title' => 'Profil',

    'tabs' => [
        'overview' => 'Übersicht',
        'sessions' => 'Sitzungen',
        'api_keys' => 'API-Schlüssel',
        'password' => 'Passwort',
        'passkeys' => 'Passkeys',
        'activity' => 'Aktivitäten',
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
        'current_password' => 'Aktuelles Passwort',
        'new_password' => 'Neues Passwort',
        'confirm_password' => 'Passwort bestätigen',
    ],

    'passkeys' => [
        'name' => 'Name',
        'last_used' => 'Zuletzt verwendet',

        'notifications' => [
            'passkey_created' => 'Passkey erfolgreich erstellt.',
            'passkey_deleted' => 'Passkey erfolgreich gelöscht.',
        ],
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

    'activity' => [
        'title' => 'Aktivitäten',
        'description' => 'Beschreibung',
        'caused_by' => 'Ausgeführt von',
        'subject' => 'Subjekt',
        'performed_at' => 'Durchgeführt am',
        'unknown_causer' => 'Unbekannter Ausführer',
        'unknown_subject' => 'Unbekanntes Subjekt',
        'pagination_previous' => 'Vorherige',
        'pagination_next' => 'Nächste',
        'pagination_text' => 'Zeige :first bis :last von :total Einträgen',

        'details' => [
            'title' => 'Aktivitätsdetails',
            'old_values' => 'Alte Werte',
            'new_values' => 'Neue Werte',
        ],
    ],

    'api_keys' => [
        'title' => 'API-Schlüssel',
        'name' => 'Name',
        'permissions' => 'Berechtigungen',
        'last_used' => 'Zuletzt verwendet',
        'never_used' => 'Nie verwendet',

        'buttons' => [
            'create_api_key' => 'API-Schlüssel erstellen',
            'api_docs' => 'API Dokumentation',
        ],

        'modals' => [
            'create_api_key' => [
                'title' => 'API-Schlüssel erstellen',

                'generated_key' => 'API-Schlüssel',
                'generated_key_description' => 'Dieser Schlüssel wurde generiert. Bitte speichere ihn an einem sicheren Ort, da er nach dem Schließen dieses Fensters nicht mehr angezeigt wird.',

                'buttons' => [
                    'create_api_key' => 'API-Schlüssel erstellen',
                ],

                'notifications' => [
                    'api_key_created' => 'API-Schlüssel erfolgreich erstellt.',
                ],
            ],
            'delete_api_key' => [
                'title' => 'API-Schlüssel löschen',
                'description' => 'Bist du sicher, dass du diesen API-Schlüssel löschen möchtest? Diese Aktion kann nicht rückgängig gemacht werden.',

                'buttons' => [
                    'delete_api_key' => 'API-Schlüssel löschen',
                ],

                'notifications' => [
                    'api_key_deleted' => 'API-Schlüssel erfolgreich gelöscht.',
                ],
            ],
        ],
    ],

    'modals' => [
        'activate_two_fa' => [
            'title' => '2-Faktor-Authentifizierung aktivieren',
            'two_fa_code' => '2-Faktor-Code',
            'invalid_two_factor_code' => 'Dieser 2-Faktor-Code ist ungültig.',

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
            'description' => 'Speichere diese Wiederherstellungscodes an einem sicheren Ort. Sie können danach nicht mehr angezeigt werden.',

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
        ],
    ],
];

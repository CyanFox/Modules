<?php

return [
    'tab_title' => 'Konto • Profil',
    'tabs' => [
        'overview' => 'Übersicht',
        'sessions' => 'Sitzungen',
    ],

    'force_actions' => [
        'change_password' => [
            'tab_title' => 'Konto • Passwort ändern',
            'new_password_confirm' => 'Neues Passwort bestätigen',

            'buttons' => [
                'change_password' => 'Passwort ändern',
            ],

            'notifications' => [
                'password_changed' => 'Passwort erfolgreich geändert.',
            ],
        ],
        'activate_two_factor' => [
            'tab_title' => 'Konto • Zwei-Faktor aktivieren',
            'two_factor_code_invalid' => 'Der Zwei-Faktor-Code ist ungültig.',
            'two_factor_code' => 'Zwei-Faktor-Code',

            'buttons' => [
                'activate_two_factor' => 'Zwei-Faktor aktivieren',
            ],
            'notifications' => [
                'two_factor_activated' => 'Zwei-Faktor erfolgreich aktiviert.',
            ],
        ],
    ],

    'change_avatar' => [
        'title' => 'Avatar ändern',
        'description' => 'Ändere deinen Avatar, indem du ein neues Bild hochlädst oder eine URL eingibst.',

        'url' => 'URL',
        'or_from_url' => 'Oder eine URL eingeben',

        'buttons' => [
            'change_avatar' => 'Avatar ändern',
            'reset_avatar' => 'Avatar zurücksetzen',
        ],
        'notifications' => [
            'avatar_changed' => 'Avatar erfolgreich geändert.',
        ],
    ],

    'overview' => [
        'language_and_theme' => [
            'title' => 'Sprache & Erscheinungsbild',
            'themes' => [
                'light' => 'Hell',
                'dark' => 'Dunkel',
            ],
            'languages' => [
                'en' => 'Englisch',
                'de' => 'Deutsch',
            ],
            'buttons' => [
                'update_language_and_theme' => 'Sprache & Erscheinungsbild aktualisieren',
            ],
            'notifications' => [
                'language_and_theme_updated' => 'Sprache & Erscheinungsbild erfolgreich aktualisiert.',
            ],
        ],
        'actions' => [
            'title' => 'Aktionen',
            'buttons' => [
                'activate_two_factor' => 'Zwei-Faktor aktivieren',
                'disable_two_factor' => 'Zwei-Faktor deaktivieren',
                'show_recovery_codes' => 'Wiederherstellungscodes anzeigen',
                'delete_account' => 'Konto löschen',
            ],

            'dialogs' => [
                'delete_account' => [
                    'title' => 'Konto löschen',
                    'description' => 'Bist du sicher, dass du dein Konto löschen möchtest? Diese Aktion kann nicht rückgängig gemacht werden.',
                    'buttons' => [
                        'delete_account' => 'Konto löschen',
                    ],
                    'notifications' => [
                        'account_deleted' => 'Konto erfolgreich gelöscht.',
                    ],
                ],
                'disable_two_factor' => [
                    'title' => 'Zwei-Faktor deaktivieren',
                    'description' => 'Bist du sicher, dass du die Zwei-Faktor-Authentifizierung deaktivieren möchtest?',
                    'buttons' => [
                        'disable_two_factor' => 'Zwei-Faktor deaktivieren',
                    ],
                    'notifications' => [
                        'two_factor_disabled' => 'Zwei-Faktor erfolgreich deaktiviert.',
                    ],
                ],
            ],

            'modals' => [
                'activate_two_factor' => [
                    'title' => 'Zwei-Faktor aktivieren',
                    'description' => 'Bist du sicher, dass du die Zwei-Faktor-Authentifizierung aktivieren möchtest?',
                    'two_factor_code_invalid' => 'Der Zwei-Faktor-Code ist ungültig.',
                    'two_factor_code' => 'Zwei-Faktor-Code',

                    'buttons' => [
                        'activate_two_factor' => 'Zwei-Faktor aktivieren',
                    ],
                    'notifications' => [
                        'two_factor_activated' => 'Zwei-Faktor erfolgreich aktiviert.',
                    ],
                ],
                'show_recovery_codes' => [
                    'title' => 'Wiederherstellungscodes anzeigen',
                    'description' => 'Die Wiederherstellungscodes werden verwendet, um auf dein Konto zuzugreifen, falls du den Zugriff auf deine Zwei-Faktor-Authentifizierungs-App verlierst.',

                    'buttons' => [
                        'regenerate' => 'Neu generieren',
                        'download' => 'Herunterladen',
                    ],
                ],
            ],
        ],
        'profile' => [
            'title' => 'Profil',
            'username_already_taken' => 'Der Benutzername ist bereits vergeben.',
            'email_already_taken' => 'Die E-Mail-Adresse ist bereits vergeben.',

            'buttons' => [
                'update_profile' => 'Profil aktualisieren',
            ],

            'notifications' => [
                'profile_updated' => 'Profil erfolgreich aktualisiert.',
            ],
        ],
        'password' => [
            'title' => 'Passwort',
            'new_password_confirmation' => 'Neues Passwort bestätigen',
            'current_password' => 'Aktuelles Passwort',

            'buttons' => [
                'update_password' => 'Passwort aktualisieren',
            ],

            'notifications' => [
                'password_updated' => 'Passwort erfolgreich aktualisiert.',
            ],
        ],
    ],

    'sessions' => [
        'title' => 'Sitzungen',
        'current_session' => 'Aktuelle Sitzung',
        'table' => [
            'ip_address' => 'IP-Adresse',
            'user_agent' => 'User Agent',
            'device' => 'Gerät',
            'last_activity' => 'Letzte Aktivität',
        ],

        'buttons' => [
            'logout_other_devices' => 'Andere Geräte abmelden',
        ],

        'notifications' => [
            'session_logged_out' => 'Sitzung erfolgreich abgemeldet.',
            'other_devices_logged_out' => 'Andere Geräte erfolgreich abgemeldet.',
        ],

        'device_types' => [
            'desktop' => 'Desktop',
            'phone' => 'Smartphone',
            'tablet' => 'Tablet',
            'unknown' => 'Unbekannt',
        ],

        'dialogs' => [
            'logout_other_devices' => [
                'title' => 'Andere Geräte abmelden',
                'description' => 'Bist du sicher, dass du alle anderen Geräte abmelden möchtest?',
                'buttons' => [
                    'logout_other_devices' => 'Geräte abmelden',
                ],
            ],
        ],
    ],
];

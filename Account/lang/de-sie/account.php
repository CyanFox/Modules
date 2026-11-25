<?php

return [
    'account' => 'Account',
    'profile' => 'Profil',
    'sessions' => 'Sitzungen',
    'activity' => 'Aktivitäten',
    'api' => 'API-Keys',
    'settings' => 'Einstellungen',

    'first_name' => 'Vorname',
    'last_name' => 'Nachname',
    'username' => 'Benutzername',
    'email' => 'E-Mail',

    'current_password' => 'Aktuelles Passwort',
    'new_password' => 'Neues Passwort',
    'confirm_new_password' => 'Neues Passwort bestätigen',

    'theme' => 'Design',
    'language' => 'Sprache',

    'name' => 'Name',
    'last_used' => 'Zuletzt verwendet',

    'disable_mfa' => [
        'title' => 'MFA Deaktivieren',
        'description' => 'Sind Sie sicher, dass Sie MFA deaktivieren möchten? Diese Aktion kann nicht rückgängig gemacht werden.',

        'buttons' => [
            'disable' => 'MFA Deaktivieren',
        ],

        'notifications' => [
            'disabled' => 'MFA erfolgreich deaktiviert',
        ],
    ],

    'delete_account' => [
        'title' => 'Account löschen',
        'description' => 'Sind Sie sicher, dass Sie Ihren Account löschen möchten? Diese Aktion kann nicht rückgängig gemacht werden.',

        'buttons' => [
            'delete' => 'Account löschen',
        ],

        'notifications' => [
            'deleted' => 'Account erfolgreich gelöscht.',
        ],
    ],

    'delete_passkey' => [
        'title' => 'Passkey löschen',
        'description' => 'Sind Sie sicher, dass Sie diesen Passkey löschen möchten? Diese Aktion kann nicht rückgängig gemacht werden.',

        'buttons' => [
            'delete' => 'Passkey löschen',
        ],

        'notifications' => [
            'deleted' => 'Passkey erfolgreich gelöscht.',
        ],
    ],

    'unlink_provider' => [
        'title' => 'Provider trennen',
        'description' => 'Sind Sie sicher, dass Sie diesen Provider trennen möchten? Diese Aktion kann nicht rückgängig gemacht werden.',

        'buttons' => [
            'unlink' => 'Provider trennen',
        ],

        'notifications' => [
            'unlinked' => 'Provider erfolgreich getrennt.',
            'last_provider' => 'Dieser Provider kann nicht getrennt werden. Bitte fügen Sie ein Passwort hinzu oder legen Sie einen neuen OAuth-Anbieter fest, um diesen zu trennen.',
        ],
    ],

    'tabs' => [
        'password' => 'Passwort',
        'passkeys' => 'Passkeys',
        'oauth' => 'OAuth-Anbieter',
    ],

    'themes' => [
        'light' => 'Hell',
        'dark' => 'Dunkel',
    ],

    'notifications' => [
        'language_updated' => 'Sprache erfolgreich aktualisiert',
        'theme_updated' => 'Theme erfolgreich aktualisiert',
        'profile_updated' => 'Profil erfolgreich aktualisiert',
        'password_changed' => 'Passwort erfolgreich geändert',
        'passkey_created' => 'Passkey erfolgreich erstellt',
    ],

    'buttons' => [
        'update_profile' => 'Profil aktualisieren',
        'change_password' => 'Passwort ändern',
        'delete_account' => 'Account löschen',
        'enable_mfa' => 'MFA aktivieren',
        'disable_mfa' => 'MFA deaktivieren',
        'regenerate_recovery_codes' => 'Wiederherstellungscodes neu generieren',
        'create_passkey' => 'Passkey erstellen',
        'link_with_provider' => 'Mit <i class=":icon mx-0.5"></i> :provider verknüpfen',
        'unlink_provider' => 'Mit <i class=":icon mx-0.5"></i> :provider trennen',
    ],
];

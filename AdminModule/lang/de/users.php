<?php

return [
    'tab_title' => 'Admin • Benutzer',
    'title' => 'Benutzer',

    'buttons' => [
        'create_user' => 'Benutzer erstellen',
    ],

    'avatar' => 'Avatar',
    'first_name' => 'Vorname',
    'last_name' => 'Nachname',
    'username' => 'Benutzername',
    'email' => 'E-Mail',
    'password' => 'Passwort',
    'password_confirmation' => 'Passwortbestätigung',
    'groups' => 'Gruppen',
    'permissions' => 'Berechtigungen',
    'two_factor_enabled' => 'Zwei-Faktor-Authentifizierung aktiviert',
    'force_activate_two_factor' => 'Zwei-Faktor-Authentifizierung erzwingen',
    'force_change_password' => 'Passwortänderung erzwingen',
    'disabled' => 'Deaktiviert',

    'create_user' => [
        'title' => 'Benutzer erstellen',
        'buttons' => [
            'create_user' => 'Benutzer erstellen',
        ],

        'notifications' => [
            'user_created' => 'Benutzer erfolgreich erstellt!',
        ],
    ],

    'update_user' => [
        'title' => 'Benutzer aktualisieren',
        'buttons' => [
            'update_user' => 'Benutzer aktualisieren',
        ],

        'notifications' => [
            'user_updated' => 'Benutzer erfolgreich aktualisiert!',
        ],
    ],

    'delete_user' => [
        'title' => 'Benutzer löschen',
        'description' => 'Bist du sicher, dass du diesen Benutzer löschen möchtest? Diese Aktion kann nicht rückgängig gemacht werden.',
        'buttons' => [
            'delete_user' => 'Benutzer löschen',
        ],

        'notifications' => [
            'user_deleted' => 'Benutzer erfolgreich gelöscht!',
        ],
    ],
];

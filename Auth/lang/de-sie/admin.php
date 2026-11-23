<?php

return [
    'oauth_providers' => [
        'tab_title' => 'OAuth Anbieter',
        'name' => 'Name',
        'color' => 'Button Farbe',
        'icon' => 'Icon',
        'discovery_url' => 'Discovery URL',
        'redirect_uri' => 'Redirect URI',
        'client_id' => 'Client ID',
        'client_secret' => 'Client Secret',
        'fields' => 'Felder',
        'account_linking' => 'Account verknüpfung',

        'oauth_field_name' => 'OAuth Feldname',
        'db_field_name' => 'Datenbank Feldname',

        'buttons' => [
            'add_field' => 'Feld hinzufügen',
        ],

        'tooltips' => [
            'create' => 'OAuth Anbieter erstellen',
            'account_linking' => 'Accounts mit vorhandenen Benutzern basierend auf ihren Benutzernamen verknüpfen',
        ],

        'hints' => [
            'icon' => 'Alle Icons finden Sie auf <x-link href="https://lucide.dev/" target="_blank">Lucide Icons</x-link> und <x-link href="https://icons.getbootstrap.com/" target="_blank">Bootstrap Icons</x-link>',
        ],

        'colors' => [
            'primary' => 'Primär',
            'secondary' => 'Sekundär',
            'success' => 'Erfolg',
            'danger' => 'Fehler',
            'warning' => 'Warnung',
            'info' => 'Info',
        ],

        'create' => [
            'title' => 'OAuth Anbieter erstellen',

            'buttons' => [
                'create' => 'Erstellen',
            ],

            'notifications' => [
                'created' => 'OAuth Anbieter erfolgreich erstellt',
            ],
        ],

        'update' => [
            'title' => 'OAuth Anbieter aktualisieren',

            'buttons' => [
                'update' => 'Aktualisieren',
            ],

            'notifications' => [
                'updated' => 'OAuth Anbieter erfolgreich aktualisiert',
            ],
        ],

        'delete' => [
            'title' => 'OAuth Anbieter löschen',
            'description' => 'Sind Sie sicher, dass Sie diesen OAuth Anbieter löschen möchten? Benutzer ohne andere Authentifizierungsmethode können sich nicht anmelden.',

            'buttons' => [
                'delete' => 'Löschen',
            ],

            'notifications' => [
                'deleted' => 'OAuth Anbieter erfolgreich gelöscht',
            ],
        ],
    ],
];

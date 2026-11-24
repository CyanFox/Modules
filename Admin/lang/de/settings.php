<?php

return [
    'tab_title' => 'Einstellungen',

    'buttons' => [
        'update' => 'Einstellungen aktualisieren',
    ],

    'notifications' => [
        'updated' => 'Einstellungen erfolgreich aktualisiert',
        'validation_failed' => 'Einige Einstellungen konnten nicht aktualisiert werden, da ihre Werte ungültig sind: :settings',
    ],

    'keys' => [
        'logo' => [
            'css' => 'Custom Logo CSS',
            'show_app_name' => 'App-Name anzeigen',
            'show_logo' => 'Logo anzeigen',
        ],
    ],

    'general' => [
        'tab_title' => 'Allgemein',
        'name' => 'App Name',
        'url' => 'App URL',
        'timezone' => 'Zeitzone',
        'default_language' => 'Standardsprache',
        'logo' => 'Logo',

        'buttons' => [
            'reset_logo' => 'Logo zurücksetzen',
        ],

        'notifications' => [
            'logo_reset' => 'Logo erfolgreich zurückgesetzt',
        ],
    ],

    'module' => [
        'tab_title' => 'Module',
        'no_settings_page' => 'Es gibt kein Modul mit einer benutzerdefinierten Einstellungsseite',
    ],

    'editor' => [
        'tab_title' => 'Editor',
        'no_module' => 'Keine Modul',

        'type' => 'Typ',
        'module' => 'Modul',
        'encrypted' => 'Verschlüsselt',
        'internal' => 'Intern',
        'auth' => 'Auth',
        'hidden' => 'Versteckt',

        'types' => [
            'string' => 'Text',
            'int' => 'Zahl',
            'float' => 'Fließkommazahl',
            'bool' => 'Boolean',
            'array' => 'Array',
            'json' => 'JSON',
        ],

        'buttons' => [
            'update' => 'Einstellung aktualisieren',
            'delete' => 'Einstellung löschen',
        ],

        'delete' => [
            'title' => 'Einstellung löschen',
            'description' => 'Bist du sicher, dass du diese Einstellung löschen möchtest? Diese Aktion kann nicht rückgängig gemacht werden.',

            'buttons' => [
                'delete' => 'Löschen',
            ],

            'notifications' => [
                'deleted' => 'Einstellung erfolgreich gelöscht',
            ],
        ],

        'tooltips' => [
            'encrypted' => 'Diese Einstellung ist in der Datenbank verschlüsselt',
            'internal' => 'Diese Einstellung ist intern und kann nicht über die API zugegriffen werden',
            'auth' => 'Diese Einstellung erfordert Authentifizierung, um zugegriffen zu werden',
            'hidden' => 'Diese Einstellung ist in der Benutzereinstellungsseite versteckt',
        ],
    ],
];

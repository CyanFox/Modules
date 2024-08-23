<?php

return [
    'tab_title' => 'Admin • Module',

    'search' => 'Suchen',
    'enabled' => 'Aktiviert',
    'disabled' => 'Deaktiviert',

    'module_settings' => 'Moduleinstellungen',
    'run_migrations' => 'Migrationen ausführen',
    'run_composer' => 'Composer-Update ausführen',
    'run_npm' => 'NPM-Run-Build ausführen',

    'no_modules' => 'Keine Module gefunden.',
    'update_available' => 'Update verfügbar',

    'buttons' => [
        'install_module' => 'Modul installieren',
    ],

    'notifications' => [
        'module_enabled' => 'Modul wurde aktiviert.',
        'module_disabled' => 'Modul wurde deaktiviert.',
        'module_migrated' => 'Modul-Datenbank-Migrationen wurden ausgeführt.',
        'module_composer_updated' => 'Composer wurde aktualisiert.',
        'module_npm_built' => 'NPM wurde gebaut.',
    ],

    'delete_module' => [
        'title' => 'Modul löschen',
        'description' => 'Bist du sicher, dass du dieses Modul löschen möchtest? Diese Aktion kann nicht rückgängig gemacht werden.',

        'notifications' => [
            'module_deleted' => 'Modul wurde gelöscht.',
        ],
    ],

    'install_module' => [
        'title' => 'Modul installieren',
        'description' => 'Stelle sicher, dass du die URL oder Zip-Datei des Moduls hast.',

        'or_from_url' => 'Oder von URL installieren',

        'module' => 'Modul',
        'url' => 'URL',

        'buttons' => [
            'install_module' => 'Modul installieren',
        ],

        'notifications' => [
            'module_installed' => 'Modul wurde installiert.',
        ],
    ],
];

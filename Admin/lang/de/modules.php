<?php

return [
    'tab_title' => 'Module',

    'search' => 'Suche',

    'name' => 'Name',
    'description' => 'Beschreibung',
    'version' => 'Version',
    'status' => 'Status',
    'authors' => 'Autoren',
    'update_available' => 'Update verfügbar (:remoteVersion)',

    'disabled' => 'Deaktiviert',
    'enabled' => 'Aktiviert',

    'notifications' => [
        'module_enabled' => 'Modul erfolgreich aktiviert.',
        'module_disabled' => 'Modul erfolgreich deaktiviert.',
        'module_requirements_not_met' => 'Modulvoraussetzungen nicht erfüllt.',
        'module_required_by_other_module' => 'Modul wird von einem anderen Modul benötigt.',
    ],

    'buttons' => [
        'install_module' => 'Modul installieren',
    ],

    'delete_module' => [
        'title' => 'Modul löschen',
        'description' => 'Bist du sicher, dass du dieses Modul löschen möchtest?',

        'buttons' => [
            'delete_module' => 'Modul löschen',
        ],

        'notifications' => [
            'module_deleted' => 'Modul erfolgreich gelöscht.',
        ],
    ],

    'install_module' => [
        'title' => 'Modul installieren',
        'file' => 'ZIP Datei',
        'url' => 'URL',

        'buttons' => [
            'install_module' => 'Modul installieren',
        ],

        'notifications' => [
            'module_installed' => 'Modul erfolgreich installiert.',
        ],
    ],
];

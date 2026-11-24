<?php

return [
    'tab_title' => 'Module',

    'name' => 'Name',
    'description' => 'Beschreibung',
    'version' => 'Version',
    'authors' => 'Authoren',
    'status' => 'Status',

    'disabled' => 'Deaktiviert',
    'enabled' => 'Aktiviert',

    'update_available' => 'Update verfügbar (:remoteVersion)',

    'tooltips' => [
        'settings' => 'Moduleinstellungen',
        'delete' => 'Modul löschen',
        'enable' => 'Modul aktivieren',
        'disable' => 'Modul deaktivieren',
    ],

    'notifications' => [
        'requirements_not_met' => 'Module Anforderungen nicht erfüllt',
        'required_by_another_module' => 'Dieses Modul kann nicht deaktiviert werden, da es von einem anderen Modul benötigt wird.',
        'enabled' => 'Module erfolgreich aktiviert',
        'disabled' => 'Module erfolgreich deaktiviert',
    ],

    'delete' => [
        'title' => 'Modul löschen',
        'description' => 'Sind Sie sicher, dass Sie dieses Modul löschen möchten? Diese Aktion kann nicht rückgängig gemacht werden.',

        'buttons' => [
            'delete' => 'Modul löschen',
        ],

        'notifications' => [
            'deleted' => 'Module erfolgreich gelöscht',
        ],
    ],
];

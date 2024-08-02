<?php

return [
    'tab_title' => 'Admin • Berechtigungen',
    'title' => 'Berechtigungen',

    'buttons' => [
        'create_permission' => 'Berechtigung erstellen',
    ],

    'name' => 'Name',
    'guard_name' => 'Guard-Name',
    'module' => 'Modul',

    'create_permission' => [
        'tab_title' => 'Admin • Berechtigungen | Berechtigung erstellen',
        'title' => 'Berechtigung erstellen',

        'notifications' => [
            'permission_created' => 'Berechtigung erfolgreich erstellt!',
        ],
    ],

    'update_permission' => [
        'tab_title' => 'Admin • Berechtigungen | Berechtigung aktualisieren',
        'title' => 'Berechtigung aktualisieren',

        'notifications' => [
            'permission_updated' => 'Berechtigung erfolgreich aktualisiert!',
        ],
    ],

    'delete_permission' => [
        'title' => 'Berechtigung löschen',
        'description' => 'Bist du sicher, dass du diese Berechtigung löschen möchtest? Diese Aktion kann nicht rückgängig gemacht werden.',

        'notifications' => [
            'permission_deleted' => 'Berechtigung erfolgreich gelöscht!',
        ],
    ],
];

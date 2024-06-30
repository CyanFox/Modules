<?php

return [
    'tab_title' => 'Berechtigungen',
    'title' => 'Berechtigungen',

    'buttons' => [
        'create_permission' => 'Berechtigung erstellen',
    ],

    'name' => 'Name',
    'guard_name' => 'Guard-Name',
    'module' => 'Modul',

    'create_permission' => [
        'title' => 'Berechtigung erstellen',
        'buttons' => [
            'create_permission' => 'Berechtigung erstellen',
        ],

        'notifications' => [
            'permission_created' => 'Berechtigung erfolgreich erstellt!',
        ],
    ],

    'update_permission' => [
        'title' => 'Berechtigung aktualisieren',
        'buttons' => [
            'update_permission' => 'Berechtigung aktualisieren',
        ],

        'notifications' => [
            'permission_updated' => 'Berechtigung erfolgreich aktualisiert!',
        ],
    ],

    'delete_permission' => [
        'title' => 'Berechtigung löschen',
        'description' => 'Bist du sicher, dass du diese Berechtigung löschen möchtest? Diese Aktion kann nicht rückgängig gemacht werden.',
        'buttons' => [
            'delete_permission' => 'Berechtigung löschen',
        ],

        'notifications' => [
            'permission_deleted' => 'Berechtigung erfolgreich gelöscht!',
        ],
    ],
];

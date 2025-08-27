<?php

return [
    'tab_title' => 'Weiterleitungen',
    'user' => 'Benutzer',
    'from' => 'Von',
    'to' => 'Nach',
    'status_code' => 'Status Code',
    'active' => 'Aktiv',
    'include_query_string' => 'Query String einbeziehen',
    'internal' => 'Intern',
    'hits' => 'Treffer',
    'last_accessed_at' => 'Zuletzt zugegriffen am',
    'groups' => 'Gruppen',
    'permissions' => 'Berechtigungen',
    'users' => 'Benutzer',
    'access' => 'Zugriff',
    'edit_access' => 'Bearbeitungszugriff',

    'status_codes' => [
        301 => '301 Moved Permanently',
        302 => '302 Found',
        303 => '303 See Other',
        307 => '307 Temporary Redirect',
        308 => '308 Permanent Redirect',
    ],

    'buttons' => [
        'create_redirect' => 'Weiterleitung erstellen',
    ],

    'create_redirect' => [
        'tab_title' => 'Weiterleitung erstellen',

        'buttons' => [
            'create_redirect' => 'Weiterleitung erstellen',
        ],

        'notifications' => [
            'redirect_created' => 'Weiterleitung erfolgreich erstellt.',
        ],
    ],

    'update_redirect' => [
        'tab_title' => 'Weiterleitung aktualisieren',

        'buttons' => [
            'update_redirect' => 'Weiterleitung aktualisieren',
        ],

        'notifications' => [
            'redirect_updated' => 'Weiterleitung erfolgreich aktualisiert.',
        ],
    ],

    'delete_redirect' => [
        'title' => 'Weiterleitung löschen',
        'description' => 'Bist du sicher, dass du diese Weiterleitung löschen möchtest? Diese Aktion kann nicht rückgängig gemacht werden.',

        'buttons' => [
            'delete_redirect' => 'Weiterleitung löschen',
        ],

        'notifications' => [
            'redirect_deleted' => 'Weiterleitung erfolgreich gelöscht.',
        ],
    ],
];

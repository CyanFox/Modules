<?php

return [
    'tab_title' => 'Ankündigungen',
    'title' => 'Titel',
    'icon' => 'Icon',
    'color' => 'Farbe',
    'description' => 'Beschreibung',
    'dismissible' => 'Schließbar',
    'disabled' => 'Deaktiviert',
    'files' => 'Dateien',
    'file_name' => 'Dateiname',
    'size' => 'Größe',
    'show_dismissed' => 'Geschlossene anzeigen',
    'hide_dismissed' => 'Geschlossene ausblenden',

    'access' => 'Zugriff',
    'groups' => 'Gruppen',
    'permissions' => 'Berechtigungen',
    'users' => 'Benutzer',

    'icon_hint' => 'Alle icons können auf <x-link href="https://lucide.dev/" target="_blank">lucide.dev</x-link> gefunden werden.',

    'colors' => [
        'info' => 'Info',
        'success' => 'Erfolg',
        'warning' => 'Warnung',
        'danger' => 'Gefahr',
    ],

    'buttons' => [
        'create_announcement' => 'Ankündigung erstellen',
    ],

    'create_announcement' => [
        'tab_title' => 'Ankündigung erstellen',
        'title' => 'Ankündigung erstellen',

        'buttons' => [
            'create_announcement' => 'Ankündigung erstellen',
        ],

        'notifications' => [
            'announcement_created' => 'Ankündigung erfolgreich erstellt.',
        ],
    ],

    'update_announcement' => [
        'tab_title' => 'Ankündigung aktualisieren',
        'title' => 'Ankündigung aktualisieren',

        'buttons' => [
            'update_announcement' => 'Ankündigung aktualisieren',
        ],

        'delete_file' => [
            'title' => 'Datei löschen',
            'description' => 'Bist du sicher, dass du diese Datei löschen möchtest? Diese Aktion kann nicht rückgängig gemacht werden.',
            'buttons' => [
                'delete_file' => 'Datei löschen',
            ],
        ],

        'notifications' => [
            'announcement_updated' => 'Ankündigung erfolgreich aktualisiert.',
            'file_deleted' => 'Datei erfolgreich gelöscht.',
        ],
    ],

    'delete_announcement' => [
        'title' => 'Ankündigung löschen',
        'description' => 'Bist du sicher, dass du diese Ankündigung löschen möchtest? Diese Aktion kann nicht rückgängig gemacht werden.',
        'buttons' => [
            'delete_announcement' => 'Ankündigung löschen',
        ],

        'notifications' => [
            'announcement_deleted' => 'Ankündigung erfolgreich gelöscht.',
        ],
    ],
];

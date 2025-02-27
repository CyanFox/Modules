<?php

return [
    'tab_title' => 'Notifications',
    'title' => 'Title',
    'message' => 'Nachricht',
    'message_hint' => 'Diese Nachricht wird als Markdown gerendert. Für mehr Informationen über Markdown, klicke <a href="https://www.markdownguide.org/basic-syntax/" target="_blank" class="underline text-blue-500">hier</a>',
    'type' => 'Typ',
    'icon' => 'Icon',
    'icon_hint' => 'Du kannst alle Icons <a href="https://lucide.dev/" target="_blank" class="underline text-blue-500">hier</a> finden',
    'dismissible' => 'Schließbar',
    'location' => 'Ort',
    'files' => 'Dateien',
    'permissions' => 'Berechtigungen',

    'types' => [
        'info' => 'Info',
        'update' => 'Update',
        'success' => 'Erfolg',
        'warning' => 'Warnung',
        'danger' => 'Error',
    ],

    'locations' => [
        'dashboard' => 'Dashboard',
        'notifications' => 'Benachrichtigungsseite',
    ],

    'buttons' => [
        'create_notification' => 'Benachrichtigung erstellen',
    ],

    'list' => [
        'tab_title' => 'Admin • Benachrichtigungen',
        'title' => 'Benachrichtigungen',
    ],

    'create_notification' => [
        'tab_title' => 'Admin • Benachrichtigungen | Benachrichtigung erstellen',
        'title' => 'Benachrichtigung erstellen',

        'notifications' => [
            'notification_created' => 'Benachrichtigung erfolgreich erstellt',
        ],
    ],

    'update_notification' => [
        'tab_title' => 'Admin • Benachrichtigungen | Benachrichtigung aktualisieren',
        'title' => 'Benachrichtigung aktualisieren',

        'notifications' => [
            'notification_updated' => 'Benachrichtigung erfolgreich aktualisiert',
        ],
    ],

    'delete_notification' => [
        'title' => 'Benachrichtigung löschen',
        'description' => 'Bist du sicher, dass du diese Benachrichtigung löschen möchtest? Diese Aktion kann nicht rückgängig gemacht werden.',

        'notifications' => [
            'notification_deleted' => 'Benachrichtigung erfolgreich gelöscht',
        ],
    ],

    'admin' => [
        'tab_title' => 'Admin • Benachrichtigungen',
        'title' => 'Benachrichtigungen',
    ],
];

<?php

return [
    'title' => 'Titel',
    'message' => 'Nachricht',
    'type' => 'Typ',
    'icon' => 'Icon',
    'dismissible' => 'Schließbar',
    'location' => 'Ort',
    'attachments' => 'Anhänge',

    'types' => [
        'info' => 'Info',
        'update' => 'Update',
        'success' => 'Erfolg',
        'warning' => 'Warnung',
        'danger' => 'Error',
    ],

    'locations' => [
        'home' => 'Startseite',
        'notificationsTab' => 'Benachrichtigungs-Tab',
    ],

    'buttons' => [
        'upload_attachments' => 'Anhänge hochladen',
    ],

    'modals' => [
        'delete_notification' => [
            'title' => 'Benachrichtigung löschen',
            'description' => 'Bist du sicher, dass du diese Benachrichtigung löschen möchtest?',

            'notifications' => [
                'notification_deleted' => 'Benachrichtigung erfolgreich gelöscht!',
            ],

            'buttons' => [
                'delete_notification' => 'Benachrichtigung löschen',
            ],
        ],
    ],

    'user_notifications' => [
        'tab_title' => 'Benachrichtigungen',
    ],

    'notification_list' => [
        'tab_title' => 'Admin • Benachrichtigungen » Benachrichtigungen',
        'title' => 'Benachrichtigungen',

        'table' => [
            'title' => 'Titel',
            'message' => 'Nachricht',
            'type' => 'Typ',
            'icon' => 'Icon',
            'dismissible' => 'Schließbar',
            'location' => 'Ort',
        ],

        'buttons' => [
            'create_notification' => 'Benachrichtigung erstellen',
        ],
    ],

    'create_notification' => [
        'tab_title' => 'Admin • Benachrichtigungen » Benachrichtigung erstellen',
        'title' => 'Benachrichtigung erstellen',

        'notifications' => [
            'notification_created' => 'Benachrichtigung erfolgreich erstellt',
        ],

        'buttons' => [
            'create_notification' => 'Benachrichtigung erstellen',
        ],
    ],

    'update_notification' => [
        'tab_title' => 'Admin • Benachrichtigungen » Benachrichtigung bearbeiten (:name)',
        'title' => 'Benachrichtigung bearbeiten (:name)',

        'notifications' => [
            'notification_updated' => 'Benachrichtigung erfolgreich aktualisiert!',
        ],

        'buttons' => [
            'update_notification' => 'Benachrichtigung aktualisieren',
        ],
    ],
];

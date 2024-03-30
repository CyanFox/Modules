<?php

return [
    'title' => 'Title',
    'message' => 'Message',
    'type' => 'Type',
    'icon' => 'Icon',
    'dismissible' => 'Dismissible',
    'location' => 'Location',
    'attachments' => 'Attachments',

    'table' => [
        'title' => 'Title',
        'message' => 'Message',
        'type' => 'Type',
        'icon' => 'Icon',
        'dismissible' => 'Dismissible',
        'location' => 'Location',
    ],

    'types' => [
        'info' => 'Info',
        'update' => 'Update',
        'success' => 'Success',
        'warning' => 'Warning',
        'danger' => 'Danger',
    ],

    'locations' => [
        'home' => 'Home',
        'notificationsTab' => 'Notifications Tab',
    ],

    'buttons' => [
        'upload_attachments' => 'Upload Attachments',
    ],

    'modals' => [
        'delete_notification' => [
            'title' => 'Delete Notification',
            'description' => 'Are you sure you want to delete this notification?',

            'notifications' => [
                'notification_deleted' => 'Notification deleted successfully!',
            ],

            'buttons' => [
                'delete_notification' => 'Delete Notification',
            ],
        ],
    ],

    'user_notifications' => [
        'tab_title' => 'Notifications',
    ],

    'notification_list' => [
        'tab_title' => 'Admin • Notifications » Notifications',
        'title' => 'Notifications',
        'table' => [
            'title' => 'Title',
            'message' => 'Message',
            'type' => 'Type',
            'icon' => 'Icon',
            'dismissible' => 'Dismissible',
            'location' => 'Location',
        ],

        'buttons' => [
            'create_notification' => 'Create Notification',
        ],
    ],

    'create_notification' => [
        'tab_title' => 'Admin • Notifications » Create Notification',
        'title' => 'Create Notification',

        'notifications' => [
            'notification_created' => 'Notification created successfully!',
        ],

        'buttons' => [
            'create_notification' => 'Create Notification',
        ],
    ],

    'update_notification' => [
        'tab_title' => 'Admin • Notifications » Update Notification (:name)',
        'title' => 'Update Notification (:name)',

        'notifications' => [
            'notification_updated' => 'Notification updated successfully!',
        ],

        'buttons' => [
            'update_notification' => 'Update Notification',
        ],
    ],
];

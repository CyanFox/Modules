<?php

return [
    'tab_title' => 'Notifications',
    'title' => 'Title',
    'message' => 'Message',
    'message_hint' => 'The message will be rendered as Markdown. For more information on Markdown, click <a href="https://www.markdownguide.org/basic-syntax/" target="_blank" class="underline text-blue-500">here</a>',
    'type' => 'Type',
    'icon' => 'Icon',
    'icon_hint' => 'You can find the list of icons <a href="https://lucide.dev/" target="_blank" class="underline text-blue-500">here</a>',
    'dismissible' => 'Dismissible',
    'location' => 'Location',
    'files' => 'Files',
    'permissions' => 'Permissions',

    'types' => [
        'info' => 'Info',
        'update' => 'Update',
        'success' => 'Success',
        'warning' => 'Warning',
        'danger' => 'Danger',
    ],

    'locations' => [
        'dashboard' => 'Dashboard',
        'notifications' => 'Notifications Tab',
    ],

    'buttons' => [
        'create_notification' => 'Create Notification',
    ],

    'list' => [
        'tab_title' => 'Admin • Notifications',
        'title' => 'Notifications',
    ],

    'create_notification' => [
        'tab_title' => 'Admin • Notifications | Create Notification',
        'title' => 'Create Notification',

        'notifications' => [
            'notification_created' => 'Notification created successfully',
        ],
    ],

    'update_notification' => [
        'tab_title' => 'Admin • Notifications | Update Notification',
        'title' => 'Update Notification',

        'notifications' => [
            'notification_updated' => 'Notification updated successfully',
        ],
    ],

    'delete_notification' => [
        'title' => 'Delete Notification',
        'description' => 'Are you sure you want to delete this notification? This action cannot be undone.',

        'notifications' => [
            'notification_deleted' => 'Notification deleted successfully',
        ],
    ],

    'admin' => [
        'tab_title' => 'Admin • Notifications',
        'title' => 'Notifications',
    ],
];

<?php

return [
    'tab_title' => 'Announcements',
    'title' => 'Title',
    'icon' => 'Icon',
    'color' => 'Color',
    'description' => 'Description',
    'dismissible' => 'Dismissible',
    'disabled' => 'Disabled',
    'files' => 'Files',
    'file_name' => 'File Name',
    'size' => 'Size',
    'show_dismissed' => 'Show Dismissed',
    'hide_dismissed' => 'Hide Dismissed',

    'access' => 'Announcement Access',
    'groups' => 'Groups',
    'permissions' => 'Permissions',
    'users' => 'Users',

    'icon_hint' => 'All icons can be found at <x-link href="https://lucide.dev/" target="_blank">lucide.dev</x-link>.',

    'colors' => [
        'info' => 'Info',
        'success' => 'Success',
        'warning' => 'Warning',
        'danger' => 'Danger',
    ],

    'buttons' => [
        'create_announcement' => 'Create Announcement',
    ],

    'create_announcement' => [
        'tab_title' => 'Create Announcement',
        'title' => 'Create Announcement',

        'buttons' => [
            'create_announcement' => 'Create Announcement',
        ],

        'notifications' => [
            'announcement_created' => 'Announcement created successfully.',
        ],
    ],

    'update_announcement' => [
        'tab_title' => 'Update Announcement',
        'title' => 'Update Announcement',

        'buttons' => [
            'update_announcement' => 'Update Announcement',
        ],

        'delete_file' => [
            'title' => 'Delete File',
            'description' => 'Are you sure you want to delete this file? This action cannot be undone.',
            'buttons' => [
                'delete_file' => 'Delete File',
            ],
        ],

        'notifications' => [
            'announcement_updated' => 'Announcement updated successfully.',
            'file_deleted' => 'File deleted successfully.',
        ],
    ],

    'delete_announcement' => [
        'title' => 'Delete Announcement',
        'description' => 'Are you sure you want to delete this announcement? This action cannot be undone.',
        'buttons' => [
            'delete_announcement' => 'Delete Announcement',
        ],

        'notifications' => [
            'announcement_deleted' => 'Announcement deleted successfully.',
        ],
    ],
];

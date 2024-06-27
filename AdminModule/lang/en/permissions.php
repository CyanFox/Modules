<?php

return [
    'tab_title' => 'Permissions',
    'title' => 'Permissions',

    'buttons' => [
        'create_permission' => 'Create Permission',
    ],

    'name' => 'Name',
    'guard_name' => 'Guard Name',
    'module' => 'Module',

    'create_permission' => [
        'title' => 'Create Permission',
        'buttons' => [
            'create_permission' => 'Create Permission',
        ],

        'notifications' => [
            'permission_created' => 'Permission created successfully!',
        ],
    ],

    'update_permission' => [
        'title' => 'Update Permission',
        'buttons' => [
            'update_permission' => 'Update Permission',
        ],

        'notifications' => [
            'permission_updated' => 'Permission updated successfully!',
        ],
    ],

    'delete_permission' => [
        'title' => 'Delete Permission',
        'description' => 'Are you sure you want to delete this permission? This action cannot be undone.',
        'buttons' => [
            'delete_permission' => 'Delete Permission',
        ],

        'notifications' => [
            'permission_deleted' => 'Permission deleted successfully!',
        ],
    ],
];

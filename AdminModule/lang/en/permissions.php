<?php

return [
    'tab_title' => 'Admin • Permissions',
    'title' => 'Permissions',

    'buttons' => [
        'create_permission' => 'Create Permission',
    ],

    'name' => 'Name',
    'guard_name' => 'Guard Name',
    'module' => 'Module',

    'create_permission' => [
        'tab_title' => 'Admin • Permissions | Update Permission',
        'title' => 'Create Permission',

        'notifications' => [
            'permission_created' => 'Permission created successfully!',
        ],
    ],

    'update_permission' => [
        'tab_title' => 'Admin • Permissions | Update Permission',
        'title' => 'Update Permission',

        'notifications' => [
            'permission_updated' => 'Permission updated successfully!',
        ],
    ],

    'delete_permission' => [
        'title' => 'Delete Permission',
        'description' => 'Are you sure you want to delete this permission? This action cannot be undone.',

        'notifications' => [
            'permission_deleted' => 'Permission deleted successfully!',
        ],
    ],
];

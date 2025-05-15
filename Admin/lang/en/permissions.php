<?php

return [
    'tab_title' => 'Permissions',

    'name' => 'Name',
    'guard_name' => 'Guard Name',

    'buttons' => [
        'create_permission' => 'Create Permission',
    ],

    'create_permission' => [
        'tab_title' => 'Create Permission',
        'title' => 'Create Permission',

        'buttons' => [
            'create_permission' => 'Create Permission',
        ],

        'notifications' => [
            'permission_created' => 'Permission created successfully.',
        ],
    ],

    'update_permission' => [
        'tab_title' => 'Update Permission',
        'title' => 'Update Permission',

        'buttons' => [
            'update_permission' => 'Update Permission',
        ],

        'notifications' => [
            'permission_updated' => 'Permission updated successfully.',
        ],
    ],

    'delete_permission' => [
        'title' => 'Delete Permission',
        'description' => 'Are you sure you want to delete this permission?',

        'buttons' => [
            'delete_permission' => 'Delete Permission',
        ],

        'notifications' => [
            'permission_deleted' => 'Permission deleted successfully.',
        ],
    ],
];

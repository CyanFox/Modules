<?php

return [
    'tab_title' => 'Groups',
    'title' => 'Groups',

    'buttons' => [
        'create_group' => 'Create Group',
    ],

    'name' => 'Name',
    'guard_name' => 'Guard Name',
    'module' => 'Module',
    'permissions' => 'Permissions',

    'create_group' => [
        'title' => 'Create Group',
        'buttons' => [
            'create_group' => 'Create Group',
        ],

        'notifications' => [
            'group_created' => 'Group created successfully!',
        ],
    ],

    'update_group' => [
        'title' => 'Update Group',
        'buttons' => [
            'update_group' => 'Update Group',
        ],

        'notifications' => [
            'group_updated' => 'Group updated successfully!',
        ],
    ],

    'delete_group' => [
        'title' => 'Delete Group',
        'description' => 'Are you sure you want to delete this group? This action cannot be undone.',
        'buttons' => [
            'delete_group' => 'Delete Group',
        ],

        'notifications' => [
            'group_deleted' => 'Group deleted successfully!',
        ],
    ],
];

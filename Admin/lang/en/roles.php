<?php

return [
    'tab_title' => 'Roles',
    'name' => 'Name',
    'guard_name' => 'Guard Name',
    'guard_name_hint' => 'Do not change this unless you know what you are doing',
    'permissions' => 'Permissions',

    'buttons' => [
        'create' => 'Create Role'
    ],

    'tooltips' => [
        'update' => 'Update Role',
        'delete' => 'Delete Role'
    ],

    'delete' => [
        'title' => 'Delete Role',
        'description' => 'Are you sure you want to delete this role? This action cannot be undone.',

        'buttons' => [
            'delete' => 'Delete Role'
        ],

        'notifications' => [
            'deleted' => 'Role deleted successfully'
        ]
    ],

    'create' => [
        'tab_title' => 'Create Role',

        'buttons' => [
            'create' => 'Create Role'
        ],

        'notifications' => [
            'created' => 'Role created successfully'
        ]
    ],

    'update' => [
        'tab_title' => 'Update Role',

        'buttons' => [
            'update' => 'Update Role'
        ],

        'notifications' => [
            'updated' => 'Role updated successfully'
        ]
    ]
];

<?php

return [
    'tab_title' => 'Permissions',
    'name' => 'Name',
    'guard_name' => 'Guard Name',
    'guard_name_hint' => 'Do not change this unless you know what you are doing',
    'permissions' => 'Permissions',

    'buttons' => [
        'create' => 'Create Permission'
    ],

    'tooltips' => [
        'update' => 'Update Permission',
        'delete' => 'Delete Permission'
    ],

    'delete' => [
        'title' => 'Delete Permission',
        'description' => 'Are you sure you want to delete this permission? This action cannot be undone.',

        'buttons' => [
            'delete' => 'Delete Permission'
        ],

        'notifications' => [
            'deleted' => 'Permission deleted successfully'
        ]
    ],

    'create' => [
        'tab_title' => 'Create Permission',

        'buttons' => [
            'create' => 'Create Permission'
        ],

        'notifications' => [
            'created' => 'Permission created successfully'
        ]
    ],

    'update' => [
        'tab_title' => 'Update Permission',

        'buttons' => [
            'update' => 'Update Permission'
        ],

        'notifications' => [
            'updated' => 'Permission updated successfully'
        ]
    ]
];

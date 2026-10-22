<?php

return [
    'tab_title' => 'Modules',

    'name' => 'Name',
    'description' => 'Description',
    'version' => 'Version',
    'authors' => 'Authors',
    'status' => 'Status',

    'disabled' => 'Disabled',
    'enabled' => 'Enabled',

    'update_available' => 'Update available (:remoteVersion)',

    'tooltips' => [
        'delete' => 'Delete module',
        'enable' => 'Enable module',
        'disable' => 'Disable module',
    ],

    'notifications' => [
        'requirements_not_met' => 'Module requirements not met',
        'required_by_another_module' => 'This module is required by another module and cannot be disabled',
        'enabled' => 'Module enabled successfully',
        'disabled' => 'Module disabled successfully',
    ],

    'delete' => [
        'title' => 'Delete module',
        'description' => 'Are you sure you want to delete this module? This action cannot be undone.',

        'buttons' => [
            'delete' => 'Delete Module',
        ],

        'notifications' => [
            'deleted' => 'Module deleted successfully',
        ],
    ]
];

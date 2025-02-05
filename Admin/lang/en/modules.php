<?php

return [
    'tab_title' => 'Modules',

    'search' => 'Search',

    'name' => 'Name',
    'description' => 'Description',
    'version' => 'Version',
    'status' => 'Status',
    'authors' => 'Authors',
    'update_available' => 'Update Available',

    'disabled' => 'Disabled',
    'enabled' => 'Enabled',

    'notifications' => [
        'module_enabled' => 'Module successfully enabled.',
        'module_disabled' => 'Module successfully disabled.',
        'module_requirements_not_met' => 'The requirements for this module are not met.',
        'module_required_by_other_module' => 'This module is required by another module and cannot be disabled.',
    ],

    'buttons' => [
        'install_module' => 'Install Module',
    ],

    'delete_module' => [
        'title' => 'Delete Module',
        'description' => 'Are you sure you want to delete this module? This action cannot be undone.',

        'buttons' => [
            'delete_module' => 'Delete Module',
        ],

        'notifications' => [
            'module_deleted' => 'Module successfully deleted.',
        ],
    ],

    'install_module' => [
        'title' => 'Install Module',
        'file' => 'ZIP File',
        'url' => 'URL',

        'buttons' => [
            'install_module' => 'Install Module',
        ],

        'notifications' => [
            'module_installed' => 'Module successfully installed.',
        ],
    ],
];

<?php

return [
    'tab_title' => 'Admin • Modules',

    'search' => 'Search',
    'enabled' => 'Enabled',
    'disabled' => 'Disabled',
    'unknown' => 'Unknown',

    'module_settings' => 'Module Settings',
    'run_migrations' => 'Run Migrations',
    'run_composer' => 'Run composer update',
    'run_npm' => 'Run npm run build',

    'no_modules' => 'No modules found.',
    'update_available' => 'Update Available',

    'buttons' => [
        'install_module' => 'Install Module',
    ],

    'notifications' => [
        'module_enabled' => 'Module has been enabled.',
        'module_disabled' => 'Module has been disabled.',
        'module_migrated' => 'Module DB migrations have been run.',
        'module_composer_updated' => 'Composer has been updated.',
        'module_npm_built' => 'NPM has been built.',
    ],

    'delete_module' => [
        'title' => 'Delete Module',
        'description' => 'Are you sure you want to delete this module? This action cannot be undone.',

        'notifications' => [
            'module_deleted' => 'Module has been deleted.',
        ],
    ],

    'install_module' => [
        'title' => 'Install Module',
        'description' => 'Make sure you have the module\'s URL or zip file.',

        'or_from_url' => 'Or install from URL',

        'module' => 'Module',
        'url' => 'URL',

        'buttons' => [
            'install_module' => 'Install Module',
        ],

        'notifications' => [
            'module_installed' => 'Module has been installed.',
        ],
    ],
];

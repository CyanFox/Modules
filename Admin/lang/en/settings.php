<?php

return [
    'tab_title' => 'Settings',

    'buttons' => [
        'update' => 'Update Settings'
    ],

    'notifications' => [
        'updated' => 'Settings updated successfully',
        'validation_failed' => 'Some settings could not be updated because their values are invalid: :settings',
    ],

    'keys' => [
        'logo' => [
            'css' => 'Custom Logo CSS',
            'show_app_name' => 'Show App Name',
            'show_logo' => 'Show Logo',
        ],
    ],

    'general' => [
        'tab_title' => 'General',
        'name' => 'App Name',
        'url' => 'App URL',
        'timezone' => 'Timezone',
        'default_language' => 'Default Language',
        'logo' => 'Logo',

        'buttons' => [
            'reset_logo' => 'Reset Logo'
        ],

        'notifications' => [
            'logo_reset' => 'Logo Reset successfully'
        ],
    ],

    'module' => [
        'tab_title' => 'Module',
        'no_settings_page' => 'There is no module with a custom settings page'
    ],

    'editor' => [
        'tab_title' => 'Editor',
        'no_module' => 'No Module',

        'type' => 'Type',
        'module' => 'Module',
        'encrypted' => 'Encrypted',
        'internal' => 'Internal',
        'auth' => 'Auth',
        'hidden' => 'Hidden',

        'types' => [
            'string' => 'String',
            'int' => 'Integer',
            'float' => 'Float',
            'bool' => 'Boolean',
            'array' => 'Array',
            'json' => 'JSON',
        ],

        'buttons' => [
            'update' => 'Update Setting',
            'delete' => 'Delete Setting'
        ],

        'delete' => [
            'title' => 'Delete Setting',
            'description' => 'Are you sure you want to delete this setting? This action cannot be undone.',

            'buttons' => [
                'delete' => 'Delete'
            ],

            'notifications' => [
                'deleted' => 'Setting deleted successfully'
            ]
        ],

        'tooltips' => [
            'encrypted' => 'This setting is encrypted in the database',
            'internal' => 'This setting is internal and cannot be accessed by the API',
            'auth' => 'This setting requires authentication to access',
            'hidden' => 'This setting is hidden in the user settings page'
        ],
    ]
];

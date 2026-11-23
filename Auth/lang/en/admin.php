<?php

return [
    'oauth_providers' => [
        'tab_title' => 'OAuth Providers',
        'name' => 'Name',
        'color' => 'Button Color',
        'icon' => 'Icon',
        'discovery_url' => 'Discovery URL',
        'redirect_uri' => 'Redirect URI',
        'client_id' => 'Client ID',
        'client_secret' => 'Client Secret',
        'fields' => 'Fields',
        'account_linking' => 'Account Linking',

        'oauth_field_name' => 'OAuth Field Name',
        'db_field_name' => 'Database Field Name',

        'buttons' => [
            'add_field' => 'Add Field',
        ],

        'tooltips' => [
            'create' => 'Create OAuth Provider',
            'account_linking' => 'Link accounts with existing users based on their usernames',
        ],

        'hints' => [
            'icon' => 'All icons can be found at <x-link href="https://lucide.dev/" target="_blank">Lucide Icons</x-link> and <x-link href="https://icons.getbootstrap.com/" target="_blank">Bootstrap Icons</x-link>',
        ],

        'colors' => [
            'primary' => 'Primary',
            'secondary' => 'Secondary',
            'success' => 'Success',
            'danger' => 'Danger',
            'warning' => 'Warning',
            'info' => 'Info',
        ],

        'create' => [
            'title' => 'Create OAuth Provider',

            'buttons' => [
                'create' => 'Create',
            ],

            'notifications' => [
                'created' => 'OAuth Provider created successfully',
            ],
        ],

        'update' => [
            'title' => 'Update OAuth Provider',

            'buttons' => [
                'update' => 'Update',
            ],

            'notifications' => [
                'updated' => 'OAuth Provider updated successfully',
            ],
        ],

        'delete' => [
            'title' => 'Delete OAuth Provider',
            'description' => 'Are you sure you want to delete this OAuth Provider? Users with no other Authentication method will be unable to log in.',

            'buttons' => [
                'delete' => 'Delete',
            ],

            'notifications' => [
                'deleted' => 'OAuth Provider deleted successfully',
            ],
        ],
    ],
];

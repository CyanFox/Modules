<?php

return [
    'tab_title' => 'Redirects',
    'user' => 'User',
    'from' => 'From',
    'to' => 'To',
    'status_code' => 'Status Code',
    'active' => 'Active',
    'include_query_string' => 'Include Query String',
    'internal' => 'Internal',
    'hits' => 'Hits',
    'last_accessed_at' => 'Last Accessed At',
    'groups' => 'Groups',
    'permissions' => 'Permissions',
    'users' => 'Users',
    'access' => 'Access',
    'edit_access' => 'Edit Access',

    'status_codes' => [
        301 => '301 Moved Permanently',
        302 => '302 Found',
        303 => '303 See Other',
        307 => '307 Temporary Redirect',
        308 => '308 Permanent Redirect',
    ],

    'buttons' => [
        'create_redirect' => 'Create Redirect',
    ],

    'create_redirect' => [
        'tab_title' => 'Create Redirect',

        'buttons' => [
            'create_redirect' => 'Create Redirect',
        ],

        'notifications' => [
            'redirect_created' => 'Redirect created successfully.',
        ],
    ],

    'update_redirect' => [
        'tab_title' => 'Update Redirect',

        'buttons' => [
            'update_redirect' => 'Update Redirect',
        ],

        'notifications' => [
            'redirect_updated' => 'Redirect updated successfully.',
        ],
    ],

    'delete_redirect' => [
        'title' => 'Delete Redirect',
        'description' => 'Are you sure you want to delete this redirect? This action cannot be undone.',

        'buttons' => [
            'delete_redirect' => 'Delete Redirect',
        ],

        'notifications' => [
            'redirect_deleted' => 'Redirect deleted successfully.',
        ],
    ],
];

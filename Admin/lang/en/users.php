<?php

return [
    'tab_title' => 'Users',
    'avatar' => 'Avatar',
    'first_name' => 'First Name',
    'last_name' => 'Last Name',
    'username' => 'Username',
    'email' => 'Email',
    'mfa_enabled' => 'MFA Enabled',
    'password' => 'Password',
    'confirm_password' => 'Confirm Password',
    'roles' => 'Roles',
    'permissions' => 'Permissions',
    'disabled' => 'Disabled',
    'disable_mfa' => 'Disable MFA',
    'force_password_change' => 'Force Password Change',
    'force_mfa' => 'Force MFA',
    'last_activity' => 'Last Activity',

    'buttons' => [
        'create' => 'Create User',
    ],

    'tooltips' => [
        'impersonate' => 'Impersonate User',
        'update' => 'Update User',
        'delete' => 'Delete User',
    ],

    'delete' => [
        'title' => 'Delete User',
        'description' => 'Are you sure you want to delete this user? This action cannot be undone.',

        'buttons' => [
            'delete' => 'Delete User',
        ],

        'notifications' => [
            'deleted' => 'User deleted successfully.',
        ],
    ],

    'create' => [
        'tab_title' => 'Create User',

        'buttons' => [
            'create' => 'Create User',
        ],

        'notifications' => [
            'created' => 'User created successfully.',
        ],
    ],

    'update' => [
        'tab_title' => 'Update User',

        'buttons' => [
            'update' => 'Update User',
        ],

        'notifications' => [
            'updated' => 'User updated successfully.',
        ],
    ],
];

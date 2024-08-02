<?php

return [
    'tab_title' => 'Admin • Users',
    'title' => 'Users',

    'buttons' => [
        'create_user' => 'Create User',
    ],

    'avatar' => 'Avatar',
    'first_name' => 'First Name',
    'last_name' => 'Last Name',
    'username' => 'Username',
    'email' => 'Email',
    'password' => 'Password',
    'password_confirmation' => 'Password Confirmation',
    'groups' => 'Groups',
    'permissions' => 'Permissions',
    'two_factor_enabled' => 'Two Factor Enabled',
    'force_activate_two_factor' => 'Force Activate Two Factor',
    'force_change_password' => 'Force Change Password',
    'disabled' => 'Disabled',

    'create_user' => [
        'tab_title' => 'Admin • Users | Create User',
        'title' => 'Create User',

        'notifications' => [
            'user_created' => 'User created successfully!',
        ],
    ],

    'update_user' => [
        'tab_title' => 'Admin • Users | Update User',
        'title' => 'Update User',

        'notifications' => [
            'user_updated' => 'User updated successfully!',
        ],
    ],

    'delete_user' => [
        'title' => 'Delete User',
        'description' => 'Are you sure you want to delete this user? This action cannot be undone.',

        'notifications' => [
            'user_deleted' => 'User deleted successfully!',
        ],
    ],
];

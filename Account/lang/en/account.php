<?php

return [
    'account' => 'Account',
    'profile' => 'Profile',
    'sessions' => 'Sessions',
    'activity' => 'Activity',
    'api' => 'API Keys',

    'first_name' => 'First Name',
    'last_name' => 'Last Name',
    'username' => 'Username',
    'email' => 'Email',

    'current_password' => 'Current Password',
    'new_password' => 'New Password',
    'confirm_new_password' => 'Confirm New Password',

    'theme' => 'Theme',
    'language' => 'Language',

    'name' => 'Name',
    'last_used' => 'Last Used',

    'disable_mfa' => [
        'title' => 'Disable MFA',
        'description' => 'Are you sure you want to disable MFA? This will make your account more vulnerable',

        'buttons' => [
            'disable' => 'Disable MFA'
        ],

        'notifications' => [
            'disabled' => 'MFA disabled successfully'
        ],
    ],

    'delete_account' => [
        'title' => 'Delete Account',
        'description' => 'Are you sure you want to delete your account? This action cannot be undone.',

        'buttons' => [
            'delete' => 'Delete Account'
        ],

        'notifications' => [
            'deleted' => 'Account deleted successfully.'
        ],
    ],

    'delete_passkey' => [
        'title' => 'Delete Passkey',
        'description' => 'Are you sure you want to delete this passkey? This action cannot be undone.',

        'buttons' => [
            'delete' => 'Delete Passkey'
        ],

        'notifications' => [
            'deleted' => 'Passkey deleted successfully.'
        ],
    ],

    'tabs' => [
        'password' => 'Password',
        'passkeys' => 'Passkeys',
    ],

    'themes' => [
        'light' => 'Light',
        'dark' => 'Dark',
    ],

    'languages' => [
        'en' => 'English',
        'de' => 'German',
    ],

    'notifications' => [
        'language_updated' => 'Language updated successfully',
        'theme_updated' => 'Theme updated successfully',
        'profile_updated' => 'Profile updated successfully',
        'password_changed' => 'Password changed successfully',
        'passkey_created' => 'Passkey created successfully',
    ],

    'buttons' => [
        'update_profile' => 'Update Profile',
        'change_password' => 'Change Password',
        'delete_account' => 'Delete Account',
        'enable_mfa' => 'Enable MFA',
        'disable_mfa' => 'Disable MFA',
        'regenerate_recovery_codes' => 'Regenerate Recovery Codes',
        'create_passkey' => 'Create Passkey',
    ],
];

<?php

return [
    'tab_title' => 'Profile',

    'tabs' => [
        'overview' => 'Overview',
        'sessions' => 'Sessions',
        'api_keys' => 'API Keys',
        'password' => 'Password',
        'passkeys' => 'Passkeys',
        'activity' => 'Activity',
        'connected_devices' => 'Connected Devices',
    ],

    'notifications' => [
        'profile_updated' => 'Profile updated successfully.',
        'password_updated' => 'Password updated successfully.',
    ],

    'language_and_theme' => [
        'title' => 'Language & Theme',
        'language' => 'Language',
        'theme' => 'Theme',

        'languages' => [
            'en' => 'English',
            'de' => 'German',
        ],
        'themes' => [
            'light' => 'Light',
            'dark' => 'Dark',
        ],
    ],

    'actions' => [
        'title' => 'Actions',

        'buttons' => [
            'activate_two_factor' => 'Activate 2FA',
            'disable_two_factor' => 'Disable 2FA',
            'regenerate_recovery_codes' => 'Regenerate Recovery Codes',
            'delete_account' => 'Delete Account',
        ],
    ],
    'profile' => [
        'title' => 'Profile',

        'first_name' => 'First Name',
        'last_name' => 'Last Name',
        'username' => 'Username',
        'email' => 'Email',
    ],

    'password' => [
        'current_password' => 'Current Password',
        'new_password' => 'New Password',
        'confirm_password' => 'Confirm Password',
    ],

    'passkeys' => [
        'name' => 'Name',
        'last_used' => 'Last Used',

        'notifications' => [
            'passkey_created' => 'Passkey created successfully.',
            'passkey_deleted' => 'Passkey deleted successfully.',
        ],
    ],

    'sessions' => [
        'title' => 'Sessions',
        'ip_address' => 'IP Address',
        'user_agent' => 'User Agent',
        'platform' => 'Platform',
        'last_active' => 'Last Active',

        'device_types' => [
            'desktop' => 'Desktop',
            'mobile' => 'Mobile',
            'tablet' => 'Tablet',
            'other' => 'Other',
        ],

        'buttons' => [
            'logout_all' => 'Logout all Sessions',
        ],

        'modals' => [
            'logout_all' => [
                'title' => 'Logout all Sessions',
                'description' => 'Are you sure you want to logout all other sessions?',
                'confirm' => 'Yes, logout all other sessions',
            ],
        ],

        'notifications' => [
            'logged_out' => 'Logged out successfully.',
            'logged_out_all' => 'Logged out all other sessions successfully.',
        ],
    ],

    'activity' => [
        'title' => 'Activities',
        'description' => 'Description',
        'caused_by' => 'Caused by',
        'subject' => 'Subject',
        'performed_at' => 'Performed at',
        'unknown_causer' => 'Unknown Causer',
        'unknown_subject' => 'Unknown Subject',
        'pagination_previous' => 'Previous',
        'pagination_next' => 'Next',
        'pagination_text' => 'Showing :first to :last of :total entries',

        'details' => [
            'title' => 'Activity Details',
            'old_values' => 'Old Values',
            'new_values' => 'New Values',
        ],
    ],

    'api_keys' => [
        'title' => 'API Keys',
        'name' => 'Name',
        'permissions' => 'Permissions',
        'last_used' => 'Last Used',
        'never_used' => 'Never Used',

        'buttons' => [
            'create_api_key' => 'Create API Key',
            'api_docs' => 'API Documentation',
        ],

        'modals' => [
            'create_api_key' => [
                'title' => 'Create API Key',

                'generated_key' => 'API Key',
                'generated_key_description' => 'This key is generated and will not be shown again. Please copy it now.',

                'buttons' => [
                    'create_api_key' => 'Create API Key',
                ],

                'notifications' => [
                    'api_key_created' => 'API Key created successfully.',
                ],
            ],
            'delete_api_key' => [
                'title' => 'Delete API Key',
                'description' => 'Are you sure you want to delete this API key? This action cannot be undone.',

                'buttons' => [
                    'delete_api_key' => 'Delete API Key',
                ],

                'notifications' => [
                    'api_key_deleted' => 'API Key deleted successfully.',
                ],
            ],
        ],
    ],

    'connected_devices' => [
        'title' => 'Connected Devices',
        'name' => 'Name',
        'last_used' => 'Last Used',
        'never_used' => 'Never Used',

        'buttons' => [
            'connect_device' => 'Connect Device',
        ],

        'modals' => [
            'connect_device' => [
                'title' => 'Connect device',
                'description' => 'Scan the QR code or use the code below to connect your device.',
                'key' => 'Key',
            ],
            'revoke_device' => [
                'title' => 'Revoke Device',
                'description' => 'Are you sure you want to revoke this device? This action cannot be undone.',

                'buttons' => [
                    'revoke_device' => 'Revoke Device',
                ],

                'notifications' => [
                    'device_revoked' => 'Device revoked successfully.',
                ],
            ],
        ],
    ],

    'modals' => [
        'activate_two_fa' => [
            'title' => 'Activate Two Factor Authentication',
            'two_fa_code' => 'Two Factor Code',
            'invalid_two_factor_code' => 'The two factor code is invalid.',

            'notifications' => [
                'two_fa_enabled' => 'Two Factor Authentication enabled successfully.',
            ],
        ],
        'disable_two_fa' => [
            'title' => 'Disable Two Factor Authentication',
            'description' => 'Are you sure you want to disable two-factor authentication?',

            'buttons' => [
                'disable' => 'Disable',
            ],

            'notifications' => [
                'two_fa_disabled' => 'Two Factor Authentication disabled successfully.',
            ],
        ],
        'recovery_codes' => [
            'title' => 'Recovery Codes',
            'description' => 'Save these recovery codes in a secure place. You will not be able to see them again.',

            'buttons' => [
                'regenerate' => 'Regenerate',
                'download' => 'Download',
            ],
        ],
        'delete_account' => [
            'title' => 'Delete Account',
            'description' => 'Are you sure you want to delete your account?',

            'notifications' => [
                'account_deleted' => 'Account deleted successfully.',
            ],
        ],
        'change_avatar' => [
            'title' => 'Change Avatar',
            'description' => 'Change your avatar by uploading a new image or providing a URL.',
            'avatar' => 'Avatar',
            'avatar_url' => 'Avatar URL',

            'buttons' => [
                'reset' => 'Reset',
            ],

            'notifications' => [
                'avatar_changed' => 'Avatar changed successfully.',
                'avatar_reset' => 'Avatar reset successfully.',
            ],
        ],
    ],
];

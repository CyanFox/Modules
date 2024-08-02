<?php

return [
    'tab_title' => 'Account • Profile',
    'tabs' => [
        'overview' => 'Overview',
        'sessions' => 'Sessions',
    ],

    'force_actions' => [
        'change_password' => [
            'tab_title' => 'Account • Change Password',
            'new_password_confirm' => 'Confirm New Password',

            'buttons' => [
                'change_password' => 'Change Password',
            ],

            'notifications' => [
                'password_changed' => 'Password changed successfully.',
            ],
        ],
        'activate_two_factor' => [
            'tab_title' => 'Account • Activate Two Factor',
            'two_factor_code_invalid' => 'The two factor code is invalid.',
            'two_factor_code' => 'Two Factor Code',

            'buttons' => [
                'activate_two_factor' => 'Activate Two Factor',
            ],
            'notifications' => [
                'two_factor_activated' => 'Two Factor activated successfully.',
            ],
        ],
    ],

    'change_avatar' => [
        'title' => 'Change Avatar',
        'description' => 'Change your avatar by uploading a new image or entering a URL.',

        'url' => 'URL',
        'or_from_url' => 'Or enter a URL',

        'buttons' => [
            'change_avatar' => 'Change Avatar',
            'reset_avatar' => 'Reset Avatar',
        ],
        'notifications' => [
            'avatar_changed' => 'Avatar changed successfully.',
        ],
    ],

    'overview' => [
        'language_and_theme' => [
            'title' => 'Language & Theme',
            'themes' => [
                'light' => 'Light',
                'dark' => 'Dark',
            ],
            'languages' => [
                'en' => 'English',
                'de' => 'German',
            ],
            'notifications' => [
                'language_and_theme_updated' => 'Language & Theme updated successfully.',
            ],
        ],
        'actions' => [
            'title' => 'Actions',
            'buttons' => [
                'activate_two_factor' => 'Activate Two Factor',
                'disable_two_factor' => 'Disable Two Factor',
                'show_recovery_codes' => 'Show Recovery Codes',
                'delete_account' => 'Delete Account',
            ],

            'dialogs' => [
                'delete_account' => [
                    'title' => 'Delete Account',
                    'description' => 'Are you sure you want to delete your account? This action cannot be undone.',
                    'buttons' => [
                        'delete_account' => 'Delete Account',
                    ],
                    'notifications' => [
                        'account_deleted' => 'Account deleted successfully.',
                    ],
                ],
                'disable_two_factor' => [
                    'title' => 'Disable Two Factor',
                    'description' => 'Are you sure you want to disable two factor authentication?',
                    'buttons' => [
                        'disable_two_factor' => 'Disable Two Factor',
                    ],
                    'notifications' => [
                        'two_factor_disabled' => 'Two Factor disabled successfully.',
                    ],
                ],
            ],

            'modals' => [
                'activate_two_factor' => [
                    'title' => 'Activate Two Factor',
                    'description' => 'Are you sure you want to activate two factor authentication?',
                    'two_factor_code_invalid' => 'The two factor code is invalid.',
                    'two_factor_code' => 'Two Factor Code',

                    'buttons' => [
                        'activate_two_factor' => 'Activate Two Factor',
                    ],
                    'notifications' => [
                        'two_factor_activated' => 'Two Factor activated successfully.',
                    ],
                ],
                'show_recovery_codes' => [
                    'title' => 'Show Recovery Codes',
                    'description' => 'The recovery codes are used to access your account in case you lose access to your two factor authentication app.',

                    'buttons' => [
                        'regenerate' => 'Regenerate',
                        'download' => 'Download',
                    ],
                ],
            ],
        ],
        'profile' => [
            'title' => 'Profile',
            'username_already_taken' => 'The username has already been taken.',
            'email_already_taken' => 'The email has already been taken.',

            'notifications' => [
                'profile_updated' => 'Profile updated successfully.',
            ],
        ],
        'password' => [
            'title' => 'Password',
            'new_password_confirmation' => 'Confirm New Password',
            'current_password' => 'Current Password',

            'notifications' => [
                'password_updated' => 'Password updated successfully.',
            ],
        ],
    ],

    'sessions' => [
        'title' => 'Sessions',
        'current_session' => 'Current Session',
        'table' => [
            'ip_address' => 'IP Address',
            'user_agent' => 'User Agent',
            'device' => 'Device',
            'last_activity' => 'Last Activity',
        ],

        'buttons' => [
            'logout_other_devices' => 'Logout Other Devices',
        ],

        'notifications' => [
            'session_logged_out' => 'Session logged out successfully.',
            'other_devices_logged_out' => 'Other devices logged out successfully.',
        ],

        'device_types' => [
            'desktop' => 'Desktop',
            'phone' => 'Phone',
            'tablet' => 'Tablet',
            'unknown' => 'Unknown',
        ],

        'dialogs' => [
            'logout_other_devices' => [
                'title' => 'Logout Other Devices',
                'description' => 'Are you sure you want to logout all other devices?',
                'buttons' => [
                    'logout_other_devices' => 'Logout Devices',
                ],
            ],
        ],
    ],
];

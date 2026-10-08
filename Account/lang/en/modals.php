<?php

return [
    'activity_details' => [
        'title' => 'Activity Details',

        'old_values' => 'Old Values',
        'new_values' => 'New Values',
    ],

    'show_login_qr_code' => [
        'title' => 'Login QR-Code',
        'description' => 'Scan the QR-Code with your app to login. This QR-Code will expire in 5 minutes.',
    ],

    'create_api_key' => [
        'title' => 'Create API Key',
        'token_description' => 'Save this token in a secure place. You will not be able to see it again.',

        'name' => 'Name',
        'permissions' => 'Permissions',

        'buttons' => [
            'create' => 'Create API Key',
        ],

        'notifications' => [
            'created' => 'API Key created successfully.',
        ]
    ],

    'enable_mfa' => [
        'title' => 'Enable MFA',
        'code' => 'Code',

        'invalid_code' => 'The entered code is invalid.',

        'recovery_codes' => 'Recovery Codes',
        'recovery_codes_description' => 'Save these recovery codes in a secure place. You will not be able to see them again.',

        'buttons' => [
            'enable' => 'Enable MFA',
            'regenerate' => 'Regenerate Codes',
            'download' => 'Download Codes'
        ],

        'notifications' => [
            'enabled' => 'MFA enabled successfully.',
        ],
    ],

    'regenerate_recovery_codes' => [
        'title' => 'Recovery Codes',
        'description' => 'Save these recovery codes in a secure place. You will not be able to see them again.',

        'buttons' => [
            'regenerate' => 'Regenerate Codes',
            'download' => 'Download Codes'
        ],
    ]
];

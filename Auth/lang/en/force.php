<?php

return [
    'photo' => 'Photo',

    'change_password' => [
        'tab_title' => 'Change Password',
        'current_password' => 'Current Password',
        'new_password' => 'New Password',
        'confirm_new_password' => 'Confirm New Password',

        'buttons' => [
            'change_password' => 'Change Password',
        ],

        'notifications' => [
            'changed' => 'Password changed successfully',
        ],
    ],

    'mfa' => [
        'tab_title' => 'Enable MFA',
        'code' => 'Code',

        'invalid_code' => 'The entered code is invalid.',

        'recovery_codes' => 'Recovery Codes',
        'recovery_codes_description' => 'Save these recovery codes in a secure place. You will not be able to see them again.',

        'buttons' => [
            'enable' => 'Enable MFA',
            'regenerate' => 'Regenerate Codes',
            'download' => 'Download Codes',
            'finish' => 'Finish',
        ],

        'notifications' => [
            'enabled' => 'MFA enabled successfully',
        ],
    ],
];

<?php

return [
    'photo' => 'Photo',

    'change_password' => [
        'tab_title' => 'Change Password',

        'current_password' => 'Current Password',
        'new_password' => 'New Password',
        'confirm_password' => 'Confirm Password',
        'password_same' => 'The password confirmation does not match.',
        'old_password_used' => 'The new password must be different from the current password.',

        'buttons' => [
            'change_password' => 'Change Password',
        ],

        'notifications' => [
            'password_changed' => 'Password changed successfully.',
        ],
    ],

    'activate_two_factor' => [
        'tab_title' => 'Activate Two Factor',

        'current_password' => 'Current Password',
        'two_fa_code' => 'Two Factor Code',
        'invalid_two_factor_code' => 'The two factor code is invalid.',
        'recovery_codes' => 'Save these recovery codes in a secure place. You will not be able to see them again.',

        'buttons' => [
            'activate_two_fa' => 'Activate Two Factor',
            'download_recovery_codes' => 'Download Codes',
            'finish' => 'Finish',
        ],

        'notifications' => [
            'two_fa_enabled' => 'Two Factor Authentication enabled successfully.',
        ],
    ]
];

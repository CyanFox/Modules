<?php

return [
    'forgot_password' => [
        'title' => 'Reset Password Notification',
        'subject' => 'Password reset for {username}',
        'content' => 'Hello {first_name} {last_name},
You are receiving this email because we received a password reset request for your account.

Click the button below to reset your password:
{resetLink}

If you did not request a password reset, no further action is required.',
    ],
];

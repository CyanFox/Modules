<?php

return [
    'forgot_password' => [
        'title' => 'Reset Password',
        'subject' => 'Password reset for {username}',
        'content' => 'Hello {first_name} {last_name},
You are receiving this email because we received a password reset request for your account.

Click the button below to reset your password:
{reset_link}

If you did not request a password reset, no further action is required.',
    ],

    'new_session' => [
        'title' => 'New Login detected',
        'subject' => 'New login detected for {username}',
        'content' => 'Hello {first_name} {last_name},
You are receiving this email because a new login was detected for your account.
IP Address: {ip_address}
User Agent: {user_agent}
Login Time: {login_time}
If this was you, no further action is required. If you did not log in, please change your password immediately. Also, consider enabling two-factor authentication for added security.',
    ],

];

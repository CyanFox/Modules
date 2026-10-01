<?php

return [
    'forgot_password' => [
        'subject' => 'Reset Password',
        'content' => 'Hello :name,
<br>You are receiving this email because we received a password reset request for your account :username.
<br>Please click on the following link to reset your password:
<br><a href=":url">:url</a>
<br>This password reset link will expire at :expiration.
<br>If you did not request a password reset, no further action is required.',
    ]
];

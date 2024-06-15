<?php

return [
    'rate_limit' => 'Too many login attempts. Please try again in :seconds seconds.',
    'buttons' => [
        'back_to_login' => 'Back to Login',
    ],
    'login' => [
        'tab_title' => 'Login',
        'remember_me' => 'Remember Me',
        'two_factor_code' => 'Two Factor Code',
        'recovery_code' => 'Recovery Code',
        'use_two_factor' => 'Use Two Factor Code',
        'use_recovery_code' => 'Use Recovery Code',
        'user_disabled' => 'This user has been disabled.',
        'two_factor_code_invalid' => 'The two factor code is invalid.',
        'user_not_found' => 'We can\'t find a user with that username.',

        'buttons' => [
            'login' => 'Login',
            'forgot_password' => 'Forgot Password?',
            'register' => 'Register',
        ],
    ],

    'forgot_password' => [
        'tab_title' => 'Forgot Password',
        'email_not_found' => 'We can\'t find a user with that e-mail address.',
        'buttons' => [
            'send_reset_link' => 'Send Reset Link',
            'reset_password' => 'Reset Password',
        ],

        'notifications' => [
            'reset_token_expired' => 'The reset token has expired.',
            'reset_token_invalid' => 'The reset token is invalid.',
            'reset_email_sent' => 'We have e-mailed your password reset link!',
            'password_reset' => 'Your password has been reset!',
        ],
    ],

    'register' => [
        'tab_title' => 'Register',
        'username_already_taken' => 'Username already taken.',
        'email_already_taken' => 'Email already taken.',

        'buttons' => [
            'register' => 'Register',
        ],

        'notifications' => [
            'account_created' => 'Your account has been created!',
        ],
    ],
];

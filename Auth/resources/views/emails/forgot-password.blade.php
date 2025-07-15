{!! Str::markdown(str_replace(
    [
        '{username}',
        '{first_name}',
        '{last_name}',
        '{reset_link}'
    ], [
        $username,
        $firstName,
        $lastName,
        $resetLink
    ],
     settings('auth.emails.forgot_password.content', config('auth.emails.forgot_password.content')))) !!}

<x-view-integration name="auth.emails.forgot_password.content"/>

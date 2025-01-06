{!! Str::markdown(str_replace(
    [
        '{username}',
        '{firstName}',
        '{lastName}',
        '{resetLink}'
    ], [
        $username,
        $firstName,
        $lastName,
        $resetLink
    ],
     settings('auth.emails.forgot_password.content', config('auth.emails.forgot_password.content')))) !!}

<x-view-integration name="auth.emails.forgot_password.content"/>

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
     setting('authmodule.emails.forgot_password.content'))) !!}

<x-view-integration name="authmodule.emails.forgot_password.content"/>

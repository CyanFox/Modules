{!! Str::markdown(str_replace(
    [
        '{username}',
        '{first_name}',
        '{last_name}',
        '{ip_address}',
        '{user_agent}',
        '{login_time}',
    ], [
        $username,
        $firstName,
        $lastName,
        $ipAddress,
        $userAgent,
        $loginTime,
    ],
     settings('auth.emails.new_session.content', config('auth.emails.new_session.content')))) !!}

<x-view-integration name="auth.emails.new_session.content"/>

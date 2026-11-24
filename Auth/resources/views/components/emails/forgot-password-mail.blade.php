{!! __('auth::emails.forgot_password.content', [
    'name' => $user->getDisplayName(),
    'username' => $user->username,
    'url' => route('auth.forgot-password', ['token' => $token]),
    'expiration' => formatDateTime($expiration)
]) !!}

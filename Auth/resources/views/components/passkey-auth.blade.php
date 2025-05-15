<div>
    @include('passkeys::components.partials.authenticateScript')

    <form id="passkey-login-form" method="POST" action="{{ route('passkeys.login') }}">
        @csrf
    </form>

    <div onclick="authenticateWithPasskey()">
        @if ($slot->isEmpty())
            <div class="underline cursor-pointer">
                {{ __('auth::passkeys.authenticate_using_passkey') }}
            </div>
        @else
            {{ $slot }}
        @endif
    </div>
</div>

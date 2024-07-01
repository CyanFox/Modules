@if (setting('oauthmodule.authentik.enable') ||
    setting('oauthmodule.github.enable') || setting('oauthmodule.google.enable') ||
    setting('oauthmodule.discord.enable'))
    <x-divider/>
@endif
@if (setting('oauthmodule.authentik.enable'))
    <x-button :href="route('oauth.redirect', 'authentik')" class="w-full" color="orange"><i class="icon-key-round"></i>
        {{ __('oauthmodule::oauth.buttons.login_with', ['provider' => 'Authentik']) }}
    </x-button>
@endif
@if (setting('oauthmodule.github.enable'))
    <x-button :href="route('oauth.redirect', 'github')" class="w-full" color="black"><i class="icon-github"></i>
        {{ __('oauthmodule::oauth.buttons.login_with', ['provider' => 'Github']) }}
    </x-button>
@endif
@if (setting('oauthmodule.google.enable'))
    <x-button :href="route('oauth.redirect', 'google')" class="w-full" color="red"><i class="bi bi-google"></i>
        {{ __('oauthmodule::oauth.buttons.login_with', ['provider' => 'Google']) }}
    </x-button>
@endif
@if (setting('oauthmodule.discord.enable'))
    <x-button :href="route('oauth.redirect', 'discord')" class="w-full" color="blue"><i class="bi bi-discord"></i>
        {{ __('oauthmodule::oauth.buttons.login_with', ['provider' => 'Discord']) }}
    </x-button>
@endif

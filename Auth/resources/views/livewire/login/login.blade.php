<div>
    <div class="relative flex min-h-dvh flex-col">
        @hook('auth.login.unsplash.css')
        <div class="absolute inset-0 z-[-1]" style="{{ data_get($unsplash, 'css') }}"></div>
        @endhook
        <main class="flex flex-1 items-center justify-center px-4 py-8">
            <div class="w-full sm:w-md" wire:transition.navigate>
                <div class="mb-4">
                    @hook('auth.login.logo')
                    <img src="{{ settings('app.logo') }}" alt="Logo"
                         class="mx-auto" style="{{ settings('auth.logo.css') }}">
                    @endhook
                </div>

                @hook('auth.login.card')
                <x-card class="w-full space-y-4">
                    @if(settings('auth.login.enabled'))
                        @if($mfaEnabled)
                            @if($user)
                                <div class="rounded-radius border border-on-surface dark:border-on-surface-dark/50"
                                     wire:transition>
                                    <div class="flex p-1 relative">
                                        <img
                                            src="{{ $user->getAvatar() }}"
                                            alt="Avatar"
                                            class="rounded-full w-8 h-8 m-1">
                                        <p class="absolute top-1/2 left-1/2 translate-x-[-50%] translate-y-[-50%]">{{ $user->username }}</p>
                                    </div>
                                </div>

                                @shook('s.auth.login.user')
                            @endif

                            @if ($rateLimitTime > 1)
                                @hook('auth.login.rate_limit')
                                <div wire:poll.1s="setRateLimit" wire:transition>
                                    <x-alert type="error">
                                        {{ __('auth.throttle', ['seconds' => $rateLimitTime]) }}
                                    </x-alert>
                                </div>
                                @endhook
                            @endif

                            @hook('auth.login.mfa.form')
                            <form wire:submit="checkMfaCode" class="space-y-4" wire:key="mfa-form">
                                <x-input wire:model="mfaCode" :label="__('auth::login.mfa_recovery_code')"
                                         autocapitalize="none" autocorrect="off" spellcheck="false"
                                         x-init="$nextTick(() => $el.focus())" autofocus required/>

                                <x-button class="w-full" type="submit" loading="checkMfaCode">
                                    {{ __('auth::login.buttons.login') }}
                                </x-button>
                                @shook('s.auth.login.mfa.buttons')
                            </form>
                            @endhook
                        @else
                            @if(settings('auth.register.enabled'))
                                <x-tab selected-tab="login" class="justify-center text-center">
                                    <x-tab.item uuid="login" class="w-1/2">
                                        {{ __('auth::login.tabs.login') }}
                                    </x-tab.item>
                                    <x-tab.item href="{{ route('auth.register') }}" class="w-1/2" wire:navigate>
                                        {{ __('auth::login.tabs.register') }}
                                    </x-tab.item>
                                    @shook('s.auth.login.tabs')
                                </x-tab>
                            @endif

                            @if($username)
                                <div class="rounded-radius border border-on-surface dark:border-on-surface-dark/50"
                                     wire:transition>
                                    <div class="flex p-1 relative">
                                        <img
                                            src="{{ $user ? $user->getAvatar() : str_replace(['{email}','{email_md5}','{username}','{first_name}','{last_name}'], [$username,md5($username),$username,$username, $username], settings('core.default_avatar_url')) }}"
                                            alt="Avatar"
                                            class="rounded-full w-8 h-8 m-1">
                                        <p class="absolute top-1/2 left-1/2 translate-x-[-50%] translate-y-[-50%]">{{ $user ? $user->username : $username }}</p>
                                    </div>
                                </div>

                                @shook('s.auth.login.user')
                            @endif

                            @if ($rateLimitTime > 1)
                                @hook('auth.login.rate_limit')
                                <div wire:poll.1s="setRateLimit" wire:transition>
                                    <x-alert type="error">
                                        {{ __('auth.throttle', ['seconds' => $rateLimitTime]) }}
                                    </x-alert>
                                </div>
                                @endhook
                            @endif

                            @hook('auth.login.form')
                            <form wire:submit="attemptLogin" class="space-y-4">
                                <x-input wire:model="username" :label="__('auth::login.username')"
                                         wire:blur="checkIfUserExists($event.target.value)"
                                         autocomplete="username webauthn" autofocus
                                         autocapitalize="none" autocorrect="off" spellcheck="false"
                                         required/>
                                <x-password wire:model="password" :label="__('auth::login.password')"
                                            autocomplete="password"
                                            required>
                                    @if(settings('auth.forgot_password.enabled'))
                                        <x-slot:hint>
                                            <x-link href="{{ route('auth.forgot-password') }}" wire:navigate>
                                                {{ __('auth::login.forgot_password') }}
                                            </x-link>
                                        </x-slot:hint>
                                    @endif
                                </x-password>
                                <x-checkbox wire:model="rememberMe" :label="__('auth::login.remember_me')"/>

                                @if(settings('auth.login.captcha'))
                                    @hook('auth.login.captcha')
                                    <x-divider/>
                                    <img src="{{ captcha_src() }}" class="rounded-radius" alt="Captcha"/>
                                    <x-input wire:model="captcha" :label="__('auth::login.captcha')" required/>
                                    @endhook
                                @endif

                                <x-button class="w-full" type="submit" loading="attemptLogin">
                                    {{ __('auth::login.buttons.login') }}
                                </x-button>
                                @shook('s.auth.login.buttons')
                            </form>

                            <x-divider/>
                            <div class="flex justify-center">
                                <x-auth::passkey-auth>
                                    <x-link>{{ __('passkeys::passkeys.authenticate_using_passkey') }}</x-link>
                                </x-auth::passkey-auth>
                            </div>
                            @endhook
                        @endif
                    @endif
                </x-card>
                @endhook
            </div>
        </main>

        <footer class="relative flex gap-2 p-4 flex-row items-end justify-between">
            @if(data_get($unsplash, 'error') == null)
                @hook('auth.login.unsplash.utm')
                <div class="text-white">
                    <span class="text-sm" wire:ignore>
                        <a href="{{ data_get($unsplash, 'photo') }}">{{ __('auth::login.photo') }}</a>,
                        <a href="{{ data_get($unsplash, 'authorURL') }}">{{ data_get($unsplash, 'author') }}</a>,
                        <a href="{{ data_get($unsplash, 'utm') }}">Unsplash</a>
                    </span>
                </div>
                @endhook
            @endif

            <div class="sm:ml-auto">
                <x-select wire:change="changeLanguage($event.target.value)">
                    <option value="en"
                            @if(app()->getLocale() == 'en') selected @endif>{{ __('messages.languages.en') }}</option>
                    <option value="de"
                            @if(app()->getLocale() == 'de') selected @endif>{{ __('messages.languages.de') }}</option>
                    <option value="de-sie"
                            @if(app()->getLocale() == 'de') selected @endif>{{ __('messages.languages.de_sie') }}</option>
                    @shook('s.global.languages')
                </x-select>
            </div>
        </footer>
    </div>
</div>

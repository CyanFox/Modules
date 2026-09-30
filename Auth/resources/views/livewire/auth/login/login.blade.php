<div>
    <div class="flex relative min-h-screen">
        @hook('auth.login.unsplash.css')
        <div class="absolute inset-0 z-[-1]" style="{{ $unsplash['css'] }}"></div>
        @endhook
        <div class="justify-center m-auto">
            <div class="mb-4">
                @hook('auth.login.logo')
                <img src="{{ settings('app.logo') }}" alt="Logo"
                     class="mx-auto" style="{{ settings('app.logo.css') }}">
                @endhook
            </div>

            @hook('auth.login.card')
            <x-card class="md:w-sm space-y-4">
                @if(settings('auth.login.enabled'))
                    @if(settings('auth.register.enabled'))
                        <x-tab selected-tab="login" class="justify-center text-center">
                            <x-tab.item uuid="login" class="w-1/2">
                                {{ __('auth::login.tabs.login') }}
                            </x-tab.item>
                            <x-tab.item href="#" class="w-1/2" wire:navigate>
                                {{ __('auth::login.tabs.register') }}
                            </x-tab.item>
                            @shook('s.auth.login.tabs')
                        </x-tab>
                    @endif

                    @if($username)
                            <div class="rounded-2xl border border-on-surface dark:border-on-surface-dark/50"
                                 wire:transition>
                            <div class="flex p-1 relative">
                                <img
                                    src="{{ $user ? $user->avatar() : str_replace(['{email}','{email_md5}','{username}','{first_name}','{last_name}'], [$username,md5($username),$username,$username, $username], settings('core.default_avatar_url')) }}"
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

                    <form wire:submit="attemptLogin" class="space-y-4">
                        <x-input wire:model="username" :label="__('auth::login.username')"
                                 wire:blur="checkIfUserExists($event.target.value)"
                                 autocomplete="username webauthn" autofocus
                                 required/>
                        <x-password wire:model="password" :label="__('auth::login.password')" autocomplete="password"
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
                            <x-input :label="__('auth::login.captcha')" required/>
                            @endhook
                        @endif

                        <x-button class="w-full" type="submit" loading="attemptLogin">
                            {{ __('auth::login.buttons.login') }}
                        </x-button>
                        @shook('s.auth.login.buttons')
                    </form>
                @endif
            </x-card>
            @endhook
        </div>

        @if($unsplash['error'] == null)
            @hook('auth.login.unsplash.utm')
            <div class="absolute bottom-0 left-0 p-4 text-white">
                <span class="text-sm" wire:ignore>
                    <a href="{{ $unsplash['photo'] }}">{{ __('auth::login.photo') }}</a>,
                    <a href="{{ $unsplash['authorURL'] }}">{{ $unsplash['author'] }}</a>,
                    <a href="{{ $unsplash['utm'] }}">Unsplash</a>
                </span>
            </div>
            @endhook
        @endif

        <div class="absolute bottom-6 left-0 p-4 sm:bottom-0 sm:right-0 sm:left-auto">
            <x-select wire:change="changeLanguage($event.target.value)">
                <option value="en" @if(app()->getLocale() == 'en') selected @endif>English</option>
                <option value="de" @if(app()->getLocale() == 'de') selected @endif>Deutsch</option>
                @shook('s.global.languages')
            </x-select>
        </div>
    </div>
</div>

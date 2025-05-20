<div>
    <div class="flex relative min-h-screen">
        <div class="absolute inset-0 z-[-1]" style="{{ $unsplash['css'] }}"></div>
        <div class="justify-center m-auto md:w-1/3 max-w-sm w-full">
            <div class="mb-4">
                <img src="{{ settings('internal.app.logo', config('settings.logo_path')) }}" alt="Logo"
                     class="{{ settings('auth.logo_size', config('auth.logo_size')) }} mx-auto">
                <x-view-integration name="auth.login.logo"/>
            </div>

            <x-card class="space-y-4 mx-auto">
                @if(settings('auth.register.enable') && !$twoFactorEnabled)
                    <x-tab selected-tab="login" class="justify-center text-center">
                        <x-tab.item uuid="login" class="w-1/2">
                            {{ __('auth::login.tabs.login') }}
                        </x-tab.item>
                        <x-tab.item href="{{ route('auth.register') }}" class="w-1/2" wire:navigate>
                            {{ __('auth::login.tabs.register') }}
                        </x-tab.item>
                        <x-view-integration name="auth.login.card.tabs"/>
                    </x-tab>
                @endif

                @if($user)
                    <div class="rounded-2xl border border-gray-500">
                        <div class="flex p-1 relative">
                            <img
                                src="{{ $user->avatar() }}"
                                alt="Avatar"
                                class="rounded-full w-8 h-8 m-1">
                            <p class="absolute top-1/2 left-1/2 translate-x-[-50%] translate-y-[-50%]">{{ $user->username }}</p>
                        </div>
                    </div>

                    <x-view-integration name="auth.login.card.user"/>
                @endif

                @if ($rateLimitTime > 1)
                    <div wire:poll.1s="setRateLimit">
                        <x-alert type="error">
                            {{ __('auth.throttle', ['seconds' => $rateLimitTime]) }}
                        </x-alert>
                    </div>

                    <x-view-integration name="auth.login.card.rate_limit"/>
                @endif
                <div class="mb-4" wire:ignore>
                    @if($message = session()->get('authenticatePasskey::message'))
                        <x-alert type="error">
                            {{ $message }}
                        </x-alert>
                    @endif
                </div>

                @if(settings('auth.login.enable'))
                    @if($twoFactorEnabled)
                        <form class="space-y-4" wire:submit="checkTwoFactorCode">
                            @if($useRecoveryCode)
                                <x-input wire:model="twoFactorCode" label="{{ __('auth::login.recovery_code') }}"
                                         required
                                         autofocus>
                                    <x-slot:hint>
                                        <span class="hover:underline cursor-pointer"
                                              wire:click="$set('useRecoveryCode', false)">
                                            {{ __('auth::login.use_two_factor') }}
                                        </span>
                                    </x-slot:hint>
                                </x-input>
                            @else
                                <x-input wire:model="twoFactorCode" label="{{ __('auth::login.two_factor_code') }}"
                                         required
                                         autofocus>
                                    <x-slot:hint>
                                        <span class="hover:underline cursor-pointer"
                                              wire:click="$set('useRecoveryCode', true)">
                                            {{ __('auth::login.use_recovery_code') }}
                                        </span>
                                    </x-slot:hint>
                                </x-input>
                            @endif

                            <x-view-integration name="auth.login.two-fa.card.form"/>

                            <x-button class="w-full" type="submit" loading="checkTwoFactorCode">
                                {{ __('auth::login.buttons.login') }}
                            </x-button>
                            <x-view-integration name="auth.login.two-fa.card.form.buttons"/>
                        </form>
                    @else
                        <form class="space-y-4" wire:submit="attemptLogin">
                            <x-input wire:model="username" wire:blur="checkIfUserExists($event.target.value)"
                                     label="{{ __('auth::login.username') }}" required
                                     autofocus/>
                            <x-password wire:model="password" label="{{ __('auth::login.password') }}" required>
                                @if(settings('auth.forgot_password.enable'))
                                    <x-slot:hint>
                                        <a href="{{ route('auth.forgot-password') }}" class="hover:underline"
                                           wire:navigate>
                                            {{ __('auth::login.forgot_password') }}
                                        </a>
                                    </x-slot:hint>
                                @endif
                            </x-password>

                            <x-checkbox wire:model="remember" label="{{ __('auth::login.remember') }}"/>

                            <x-view-integration name="auth.login.card.form"/>

                            @if(settings('auth.login.enable.captcha', config('auth.login.captcha')))
                                <div class="gap-3 lg:flex space-y-3">
                                    <img src="{{ captcha_src('inverse') }}" class="rounded-lg lg:w-1/2 w-full"
                                         alt="Captcha">

                                    <x-input wire:model="captcha" label="{{ __('auth::login.captcha') }}" required/>

                                    <x-view-integration name="auth.login.card.captcha"/>
                                </div>
                            @endif

                            <x-button class="w-full" type="submit" loading="attemptLogin">
                                {{ __('auth::login.buttons.login') }}
                            </x-button>
                            <x-view-integration name="auth.login.card.form.buttons"/>
                        </form>
                        <x-auth::passkey-auth>
                            <x-link>{{ __('passkeys::passkeys.authenticate_using_passkey') }}</x-link>
                        </x-auth::passkey-auth>
                    @endif
                @endif

                @if(settings('auth.oauth.enable') && !$twoFactorEnabled)
                    <x-divider/>

                    <x-button class="w-full" href="{{ route('oauth.redirect', ['provider' => 'custom']) }}"
                              :color="settings('auth.oauth.login_color')">
                        {{ settings('auth.oauth.login_text') }}
                    </x-button>
                @endif

                <x-view-integration name="auth.login.card.end"/>

            </x-card>
        </div>

        @if($unsplash['error'] == null)
            <div class="absolute bottom-0 left-0 p-4 text-white">
                <span class="text-sm" wire:ignore>
                    <a href="{{ $unsplash['photo'] }}">{{ __('auth::login.photo') }}</a>,
                    <a href="{{ $unsplash['authorURL'] }}">{{ $unsplash['author'] }}</a>,
                    <a href="{{ $unsplash['utm'] }}">Unsplash</a>
                </span>
            </div>
        @endif

        <div class="absolute bottom-6 left-0 p-4 sm:bottom-0 sm:right-0 sm:left-auto">
            <x-select class="pr-5" wire:change="changeLanguage($event.target.value)">
                <option value="en" @if(app()->getLocale() == 'en') selected @endif>English</option>
                <option value="de" @if(app()->getLocale() == 'de') selected @endif>Deutsch</option>

                <x-view-integration name="auth.login.language"/>

            </x-select>
        </div>
    </div>
</div>

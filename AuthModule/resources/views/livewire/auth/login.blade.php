<div>
    <div class="flex relative min-h-screen">
        <div class="absolute inset-0 z-[-1]" style="{{ $unsplash['css'] }}"></div>
        <div class="justify-center m-auto md:w-1/3 max-w-lg">
            <x-card>
                <div class="flex items-center justify-center">
                    <img class="size-24" src="{{ asset(setting('settings.logo_path')) }}" alt="Logo">
                    <x-view-integration name="authmodule.login.logo"/>
                </div>
                <div class="space-y-4">
                    <x-view-integration name="authmodule.login.header"/>

                    @if($user)
                        <div class="rounded-2xl border border-gray-500">
                            <div class="flex p-1 relative">
                                <img
                                        src="{{ user()->getUser($user)->getAvatarURL() }}"
                                        alt="Avatar"
                                        class="rounded-full w-8 h-8 m-1">
                                <p class="absolute top-1/2 left-1/2 translate-x-[-50%] translate-y-[-50%]">{{ $user->username }}</p>
                            </div>
                        </div>

                        <x-view-integration name="authmodule.login.user"/>
                    @endif

                    @if ($rateLimitTime > 1)
                        <div wire:poll.1s="setRateLimit">
                            <x-alert icon="alert-triangle" color="yellow">
                                {{ __('authmodule::auth.rate_limit', ['seconds' => $rateLimitTime]) }}
                            </x-alert>
                        </div>

                        <x-view-integration name="authmodule.login.rate_limit"/>
                    @endif

                    @if(setting('authmodule.enable.local_login'))
                        @if ($twoFactorEnabled)
                            <form class="space-y-5" wire:submit="checkTwoFactorCode">
                                @csrf

                                <x-view-integration name="authmodule.login.two_factor.header"/>

                                @if ($useRecoveryCode)
                                    <x-input label="{{ __('authmodule::auth.login.recovery_code') }} *"
                                             wire:model="twoFactorCode"/>

                                    <x-view-integration name="authmodule.login.recovery_code.form"/>

                                    <span class="text-xs text-gray-500 cursor-pointer"
                                          wire:click="$set('useRecoveryCode', false)">
                                    {{ __('authmodule::auth.login.use_two_factor') }}
                                </span>

                                    <x-view-integration name="authmodule.login.recovery_code.footer"/>
                                @else
                                    <div class="flex justify-center">
                                        <div>
                                            <x-pin length="6"
                                                   label="{{ __('authmodule::auth.login.two_factor_code')}} *"
                                                   wire:model="twoFactorCode" numbers/>

                                            <x-view-integration name="authmodule.login.two_factor.form"/>

                                            <span class="text-sm text-gray-500 cursor-pointer"
                                                  wire:click="$set('useRecoveryCode', true)">
                                            {{ __('authmodule::auth.login.use_recovery_code') }}
                                        </span>

                                            <x-view-integration name="authmodule.login.two_factor.footer"/>
                                        </div>
                                    </div>
                                @endif

                                <x-button class="w-full" type="submit" loading="checkTwoFactorCode">
                                    {{ __('authmodule::auth.login.buttons.login') }}
                                </x-button>

                                <x-view-integration name="authmodule.login.two_factor.buttons"/>
                            </form>
                        @else
                            <form class="space-y-5" wire:submit="attemptLogin">
                                @csrf

                                <x-view-integration name="authmodule.login.form.header"/>

                                <x-input label="{{ __('authmodule::messages.username') }} *" wire:model="username"
                                         wire:blur="checkIfUserExists($event.target.value)"/>

                                <x-password label="{{ __('authmodule::messages.password') }} *" wire:model="password"/>

                                <x-checkbox label="{{ __('authmodule::auth.login.remember_me') }}"
                                            wire:model="rememberMe"/>

                                <x-view-integration name="authmodule.login.form"/>

                                @if(setting('authmodule.enable.captcha'))
                                    <div class="gap-3 lg:flex space-y-3">
                                        <img src="{{ captcha_src() }}" class="rounded-lg lg:w-1/2 w-full" alt="Captcha">

                                        <x-input label="{{ __('messages.captcha') }} *" class="w-full"
                                                 wire:model="captcha"/>

                                        <x-view-integration name="authmodule.login.captcha"/>
                                    </div>
                                @endif

                                <x-button class="w-full" loading="attemptLogin" type="submit">
                                    {{ __('authmodule::auth.login.buttons.login') }}
                                </x-button>

                                <x-view-integration name="authmodule.login.buttons"/>

                                <x-view-integration name="authmodule.login.form.footer"/>
                            </form>
                        @endif
                    @endif

                    @if (setting('authmodule.enable.register') || setting('authmodule.enable.forgot_password'))
                        <x-divider/>

                        <div class="flex flex-col md:flex-row gap-2">
                            @if(setting('authmodule.enable.register'))
                                <x-button :href="route('auth.register')" color="gray" class="w-full" wire:navigate>
                                    {{ __('authmodule::auth.login.buttons.register') }}
                                </x-button>
                            @endif

                            @if(setting('authmodule.enable.forgot_password'))
                                <x-button :href="route('auth.forgot-password', '')" class="w-full" color="gray"
                                          wire:navigate>
                                    {{ __('authmodule::auth.login.buttons.forgot_password') }}
                                </x-button>
                            @endif

                            <x-view-integration name="authmodule.login.alternative_buttons"/>
                        </div>
                    @endif

                    <x-view-integration name="authmodule.login.footer"/>
                </div>
            </x-card>
        </div>

        @if($unsplash['error'] == null)
            <div class="absolute bottom-0 left-0 p-4 text-white">
                <span class="text-sm" wire:ignore>
                    <a href="{{ $unsplash['photo'] }}">{{ __('messages.photo') }}</a>,
                    <a href="{{ $unsplash['authorURL'] }}">{{ $unsplash['author'] }}</a>,
                    <a href="https://unsplash.com/{{ setting('settings.unsplash.utm') }}">Unsplash</a>
                </span>
            </div>
        @endif
    </div>
</div>

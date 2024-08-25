<div>
    <div class="flex relative min-h-screen">
        <div class="absolute inset-0 z-[-1]" style="{{ $unsplash['css'] }}"></div>
        <div class="justify-center m-auto md:w-1/3 max-w-lg">
            <x-card>
                <div class="flex items-center justify-center">
                    <img class="size-24" src="{{ asset(setting('settings.logo_path')) }}" alt="Logo">
                    <x-view-integration name="authmodule.register.logo"/>
                </div>
                <div class="space-y-4">
                    <x-view-integration name="authmodule.register.header"/>

                    @if ($rateLimitTime > 1)
                        <div wire:poll.1s="setRateLimit">
                            <x-alert icon="alert-triangle" color="yellow">
                                {{ __('authmodule::auth.rate_limit', ['seconds' => $rateLimitTime]) }}
                            </x-alert>

                            <x-view-integration name="authmodule.register.rate_limit"/>
                        </div>
                    @endif

                    <form class="space-y-5" wire:submit="register">
                        @csrf
                        <x-view-integration name="authmodule.register.form.header"/>

                        <div class="grid md:grid-cols-2 grid-cols-1 gap-3">
                            <x-input label="{{ __('authmodule::messages.first_name') }} *" wire:model="firstName"/>

                            <x-input label="{{ __('authmodule::messages.last_name') }} *" wire:model="lastName"/>

                            <x-input label="{{ __('authmodule::messages.username') }} *" wire:model="username"/>

                            <x-input label="{{ __('authmodule::messages.email') }} *" wire:model="email"/>

                            <x-password label="{{ __('authmodule::messages.password') }} *" wire:model="password"/>

                            <x-password label="{{ __('authmodule::messages.confirm_password') }} *"
                                        wire:model="passwordConfirmation"/>
                        </div>

                        <x-view-integration name="authmodule.register.form"/>

                        @if(setting('authmodule.enable.captcha'))
                            <div class="gap-3 lg:flex space-y-3">
                                <img src="{{ captcha_src() }}" class="rounded-lg lg:w-1/2 w-full" alt="Captcha">

                                <x-input label="{{ __('messages.captcha') }} *" class="w-full" wire:model="captcha"/>

                                <x-view-integration name="authmodule.register.captcha"/>
                            </div>
                        @endif

                        <x-button class="w-full" loading="register" type="submit">
                            {{ __('authmodule::auth.register.buttons.register') }}
                        </x-button>

                        <x-view-integration name="authmodule.register.buttons"/>
                    </form>

                    <x-divider/>
                    <x-button :href="route('auth.login')" class="w-full" color="gray" wire:navigate>
                        {{ __('authmodule::auth.buttons.back_to_login') }}
                    </x-button>

                    <x-view-integration name="authmodule.register.alternative_buttons"/>


                    <x-view-integration name="authmodule.register.footer"/>
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

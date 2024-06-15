<div>
    <div class="flex min-h-screen">
        <div class="absolute inset-0 z-[-1]" style="{{ $unsplash['css'] }}"></div>
        <div class="justify-center m-auto md:w-1/3 max-w-lg">
            <x-card>
                <div class="flex items-center justify-center">
                    <img class="size-24" src="{{ asset(setting('settings.logo_path')) }}" alt="Logo">
                    <x-view-integration name="authmodule.forgot_password.logo"/>
                </div>
                <div class="space-y-4">
                    <x-view-integration name="authmodule.forgot_password.top"/>

                    @if ($rateLimitTime > 1)
                        <div wire:poll.1s="setRateLimit">
                            <x-alert icon="alert-triangle" color="yellow">
                                {{ __('authmodule::auth.rate_limit', ['seconds' => $rateLimitTime]) }}
                            </x-alert>
                        </div>
                    @endif

                    @if($resetToken)
                        <form class="space-y-5" wire:submit="resetPassword">
                            @csrf
                            <x-password :label="__('authmodule::messages.password') . ' *'" wire:model="password"/>

                            <x-password :label="__('authmodule::messages.confirm_password') . ' *'" wire:model="passwordConfirmation"/>

                            <x-view-integration name="authmodule.forgot_password.reset_form"/>

                            <x-button class="w-full" loading="resetPassword" type="submit">
                                {{ __('authmodule::auth.forgot_password.buttons.reset_password') }}
                            </x-button>
                        </form>
                    @else
                        <form class="space-y-5" wire:submit="sendResetLink">
                            @csrf
                            <x-input :label="__('authmodule::messages.email') . ' *'" wire:model="email"/>

                            <x-view-integration name="authmodule_forgot_email_form"/>

                            @if(setting('authmodule.enable.captcha'))
                                <div class="gap-3 lg:flex space-y-3">
                                    <img src="{{ captcha_src() }}" class="rounded-lg lg:w-1/2 w-full" alt="Captcha">

                                    <x-input :label="__('messages.captcha') . ' *'" class="w-full" wire:model="captcha"/>
                                </div>
                            @endif

                            <x-button class="w-full" loading="sendResetLink" type="submit">
                                {{ __('authmodule::auth.forgot_password.buttons.send_reset_link') }}
                            </x-button>
                        </form>
                    @endif

                    <x-divider />
                    <x-button :href="route('auth.login')" class="w-full" color="gray" wire:navigate>
                        {{ __('authmodule::auth.buttons.back_to_login') }}
                    </x-button>

                    <x-view-integration name="authmodule.forgot_password.bottom"/>
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

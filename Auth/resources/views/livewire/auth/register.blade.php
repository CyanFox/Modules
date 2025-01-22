<div>
    <div class="flex relative min-h-screen">
        <div class="absolute inset-0 z-[-1]" style="{{ $unsplash['css'] }}"></div>
        <div class="justify-center m-auto md:w-1/2 max-w-md w-full">
            <div class="mb-4">
                <img src="{{ settings('internal.app.logo', config('settings.logo_path')) }}" alt="Logo"
                     class="{{ settings('auth.logo_size', config('auth.logo_size')) }} mx-auto">
                <x-view-integration name="auth.register.logo"/>
            </div>

            <x-card class="space-y-4 mx-auto">
                <x-tab selected-tab="register" class="justify-center">
                    <x-tab.item href="{{ route('auth.login') }}" class="w-1/2" wire:navigate>
                        {{ __('auth::register.tabs.login') }}
                    </x-tab.item>
                    <x-tab.item class="w-1/2" uuid="register">
                        {{ __('auth::register.tabs.register') }}
                    </x-tab.item>
                    <x-view-integration name="auth.register.card.tabs"/>
                </x-tab>

                @if ($rateLimitTime > 1)
                    <div wire:poll.1s="setRateLimit">
                        <x-alert color="error">
                            <i class="icon-triangle-alert"></i>
                            {{ __('auth.throttle', ['seconds' => $rateLimitTime]) }}
                        </x-alert>
                    </div>

                    <x-view-integration name="auth.register.card.rate_limit"/>
                @endif

                <form class="space-y-4" wire:submit="register">
                    <div class="grid md:grid-cols-2 gap-4">
                        <x-input wire:model="firstName">
                            {{ __('auth::register.first_name') }}
                        </x-input>
                        <x-input wire:model="lastName">
                            {{ __('auth::register.last_name') }}
                        </x-input>

                        <x-input wire:model="email" required>
                            {{ __('auth::register.email') }}
                        </x-input>
                        <x-input wire:model="username" required>
                            {{ __('auth::register.username') }}
                        </x-input>

                        <x-password wire:model="password" required>
                            {{ __('auth::register.password') }}
                        </x-password>
                        <x-password wire:model="passwordConfirmation" required>
                            {{ __('auth::register.password_confirmation') }}
                        </x-password>
                    </div>

                    <x-view-integration name="auth.register.card.form"/>

                    @if(settings('auth.register.enable.captcha', config('auth.register.captcha')))
                        <div class="gap-3 lg:flex space-y-3">
                            <img src="{{ captcha_src('inverse') }}" class="rounded-lg lg:w-1/2 w-full" alt="Captcha">

                            <x-input wire:model="captcha" required>
                                {{ __('auth::register.captcha') }}
                            </x-input>

                            <x-view-integration name="auth.register.card.captcha"/>
                        </div>
                    @endif

                    <x-button class="w-full" type="submit" loading="register">
                        {{ __('auth::register.buttons.register') }}
                    </x-button>
                    <x-view-integration name="auth.register.card.form.buttons"/>

                </form>

                <x-view-integration name="auth.register.card.end"/>

            </x-card>
        </div>

        @if($unsplash['error'] == null)
            <div class="absolute bottom-0 left-0 p-4 text-white">
                <span class="text-sm" wire:ignore>
                    <a href="{{ $unsplash['photo'] }}">{{ __('auth::register.photo') }}</a>,
                    <a href="{{ $unsplash['authorURL'] }}">{{ $unsplash['author'] }}</a>,
                    <a href="{{ $unsplash['utm'] }}">Unsplash</a>
                </span>
            </div>
        @endif

        <div class="absolute bottom-6 left-0 p-4 sm:bottom-0 sm:right-0 sm:left-auto">
            <x-select class="max-w-sm" wire:change="changeLanguage($event.target.value)">
                <option value="en" @if(app()->getLocale() == 'en') selected @endif>English</option>
                <option value="de" @if(app()->getLocale() == 'de') selected @endif>Deutsch</option>

                <x-view-integration name="auth.register.language"/>

            </x-select>
        </div>
    </div>
</div>

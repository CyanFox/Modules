<div>
    <div class="flex relative min-h-screen">
        @hook('auth.register.unsplash.css')
        <div class="absolute inset-0 z-[-1]" style="{{ $unsplash['css'] }}"></div>
        @endhook
        <div class="justify-center m-auto" wire:transition.navigate>
            <div class="mb-4">
                @hook('auth.register.logo')
                <img src="{{ settings('app.logo') }}" alt="Logo"
                     class="mx-auto" style="{{ settings('auth.logo.css') }}">
                @endhook
            </div>

            @hook('auth.register.card')
            <x-card class="md:w-lg space-y-4">
                <x-tab selected-tab="register" class="justify-center text-center">
                    <x-tab.item href="{{ route('auth.login') }}" class="w-1/2" wire:navigate>
                        {{ __('auth::login.tabs.login') }}
                    </x-tab.item>
                    <x-tab.item uuid="register" class="w-1/2">
                        {{ __('auth::login.tabs.register') }}
                    </x-tab.item>
                    @shook('s.auth.register.tabs')
                </x-tab>

                @if ($rateLimitTime > 1)
                    @hook('auth.register.rate_limit')
                    <div wire:poll.1s="setRateLimit" wire:transition>
                        <x-alert type="error">
                            {{ __('auth.throttle', ['seconds' => $rateLimitTime]) }}
                        </x-alert>
                    </div>
                    @endhook
                @endif

                @hook('auth.register.form')
                <form wire:submit="register" class="space-y-4">
                    <div class="grid md:grid-cols-2 gap-4">
                        <x-input wire:model="firstName" :label="__('auth::register.first_name')"/>
                        <x-input wire:model="lastName" :label="__('auth::register.first_name')"/>

                        <x-input wire:model="username" :label="__('auth::register.username')" required/>
                        <x-input wire:model="email" type="email" :label="__('auth::register.email')" required/>

                        <x-password wire:model="password" :label="__('auth::register.password')" required/>
                        <x-password wire:model="passwordConfirmation" :label="__('auth::register.confirm_password')"
                                    required/>
                    </div>

                    @if(settings('auth.register.captcha'))
                        @hook('auth.register.captcha')
                        <x-divider/>
                        <img src="{{ captcha_src() }}" class="rounded-radius" alt="Captcha"/>
                        <x-input wire:model="captcha" :label="__('auth::register.captcha')" required/>
                        @endhook
                    @endif

                    <x-button class="w-full" type="submit" loading="register">
                        {{ __('auth::register.buttons.register') }}
                    </x-button>
                    @shook('s.auth.register.buttons')
                </form>
                @endhook
            </x-card>
            @endhook
        </div>

        @if($unsplash['error'] == null)
            @hook('auth.register.unsplash.utm')
            <div class="absolute bottom-0 left-0 p-4 text-white">
                <span class="text-sm" wire:ignore>
                    <a href="{{ $unsplash['photo'] }}">{{ __('auth::register.photo') }}</a>,
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

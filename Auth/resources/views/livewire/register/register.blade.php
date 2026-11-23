<div>
    <div class="relative flex min-h-dvh flex-col">
        @hook('auth.register.unsplash.css')
        <div class="absolute inset-0 z-[-1]" style="{{ data_get($unsplash, 'css') }}"></div>
        @endhook
        <main class="flex flex-1 items-center justify-center px-4 py-8">
            <div class="w-full sm:w-md" wire:transition.navigate>
                <div class="mb-4">
                    @hook('auth.register.logo')
                    <img src="{{ settings('app.logo') }}" alt="Logo"
                         class="mx-auto" style="{{ settings('auth.logo.css') }}">
                    @endhook
                </div>

                @hook('auth.register.card')
                <x-card class="w-full space-y-4">
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

                        <x-divider/>

                        <x-button class="w-full" type="submit" loading="register">
                            {{ __('auth::register.buttons.register') }}
                        </x-button>
                        @shook('s.auth.register.buttons')
                    </form>
                    @endhook
                </x-card>
                @endhook
            </div>
        </main>

        <footer class="relative flex gap-2 p-4 flex-row items-end justify-between">
            @if(data_get($unsplash, 'error') == null)
                @hook('auth.register.unsplash.utm')
                <div class="text-white">
                    <span class="text-sm" wire:ignore>
                        <a href="{{ data_get($unsplash, 'photo') }}">{{ __('auth::register.photo') }}</a>,
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

<div>
    <div class="flex relative min-h-screen">
        @hook('auth.forgot_password.unsplash.css')
        <div class="absolute inset-0 z-[-1]" style="{{ $unsplash['css'] }}"></div>
        @endhook
        <div class="justify-center m-auto" wire:transition.navigate>
            <div class="mb-4">
                @hook('auth.forgot_password.logo')
                <img src="{{ settings('app.logo') }}" alt="Logo"
                     class="mx-auto" style="{{ settings('auth.logo.css') }}">
                @endhook
            </div>

            @hook('auth.forgot_password.card')
            <x-card class="md:w-sm space-y-4">
                @if($user)
                    <div class="rounded-2xl border border-on-surface dark:border-on-surface-dark/50"
                         wire:transition>
                        <div class="flex p-1 relative">
                            <img
                                src="{{ $user->getAvatar() }}"
                                alt="Avatar"
                                class="rounded-full w-8 h-8 m-1">
                            <p class="absolute top-1/2 left-1/2 translate-x-[-50%] translate-y-[-50%]">{{ $user->username }}</p>
                        </div>
                    </div>
                    @shook('s.auth.forgot_password.user')

                    @if ($rateLimitTime > 1)
                        @hook('auth.forgot_password.rate_limit')
                        <div wire:poll.1s="setRateLimit" wire:transition>
                            <x-alert type="error">
                                {{ __('auth.throttle', ['seconds' => $rateLimitTime]) }}
                            </x-alert>
                        </div>
                        @endhook
                    @endif

                    @hook('auth.forgot_password.reset.form')
                    <form wire:submit="resetPassword" class="space-y-4">
                        <x-password wire:model="password" :label="__('auth::forgot-password.password')" required/>
                        <x-password wire:model="passwordConfirmation"
                                    :label="__('auth::forgot-password.confirm_password')" required/>

                        @if(settings('auth.forgot_password.captcha'))
                            @hook('auth.forgot_password.captcha')
                            <x-divider/>
                            <img src="{{ captcha_src() }}" class="rounded-radius" alt="Captcha"/>
                            <x-input :label="__('auth::forgot-password.captcha')" required/>
                            @endhook
                        @endif

                        <x-button class="w-full" type="submit" loading="resetPassword">
                            {{ __('auth::forgot-password.buttons.reset_password') }}
                        </x-button>
                        @shook('s.auth.forgot_password.reset.buttons')
                    </form>
                    @endhook
                @else
                    <x-tab selected-tab="forgot_password" class="justify-center text-center">
                        <x-tab.item href="{{ route('auth.login') }}" class="w-1/2" wire:navigate>
                            {{ __('auth::forgot-password.tabs.login') }}
                        </x-tab.item>
                        <x-tab.item uuid="forgot_password" class="w-1/2">
                            {{ __('auth::forgot-password.tabs.forgot_password') }}
                        </x-tab.item>
                        @shook('s.auth.forgot_password.tabs')
                    </x-tab>

                    @if ($rateLimitTime > 1)
                        @hook('auth.forgot_password.rate_limit')
                        <div wire:poll.1s="setRateLimit" wire:transition>
                            <x-alert type="error">
                                {{ __('auth.throttle', ['seconds' => $rateLimitTime]) }}
                            </x-alert>
                        </div>
                        @endhook
                    @endif

                    @hook('auth.forgot_password.link.form')
                    <form wire:submit="sendResetLink" class="space-y-4">
                        <x-input wire:model="username" :label="__('auth::forgot-password.username')"
                                 autocomplete="username" autofocus
                                 required/>

                        @if(settings('auth.forgot_password.captcha'))
                            @hook('auth.forgot_password.captcha')
                            <x-divider/>
                            <img src="{{ captcha_src() }}" class="rounded-radius" alt="Captcha"/>
                            <x-input wire:model="captcha" :label="__('auth::forgot-password.captcha')" required/>
                            @endhook
                        @endif

                        <x-button class="w-full" type="submit" loading="sendResetLink">
                            {{ __('auth::forgot-password.buttons.send_rest_link') }}
                        </x-button>
                        @shook('s.auth.forgot_password.link.buttons')
                    </form>
                    @endhook
                @endif
            </x-card>
            @endhook
        </div>

        @if($unsplash['error'] == null)
            @hook('auth.forgot_password.unsplash.utm')
            <div class="absolute bottom-0 left-0 p-4 text-white">
                <span class="text-sm" wire:ignore>
                    <a href="{{ $unsplash['photo'] }}">{{ __('auth::forgot-password.photo') }}</a>,
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

<div>
    <div class="flex relative min-h-screen">
        <div class="absolute inset-0 z-[-1]" style="{{ $unsplash['css'] }}"></div>
        <div class="justify-center m-auto md:w-1/3 max-w-sm w-full">
            <div class="mb-4">
                <img src="{{ asset('img/Logo.svg') }}" alt="Logo"
                     class="{{ settings('auth.logo_size', config('auth.logo_size')) }} mx-auto">
                <x-view-integration name="auth.forgot-password.logo"/>
            </div>

            <x-card class="space-y-4 mx-auto">
                <x-tab selected-tab="forgot-password" class="justify-center">
                    <x-tab.item href="{{ route('auth.login') }}" class="w-1/2" wire:navigate>
                        {{ __('auth::forgot-password.tabs.login') }}
                    </x-tab.item>
                    <x-tab.item uuid="forgot-password" class="w-1/2">
                        {{ __('auth::forgot-password.tabs.forgot_password') }}
                    </x-tab.item>
                    <x-view-integration name="auth.forgot-password.card.tabs"/>
                </x-tab>

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

                    <x-view-integration name="auth.forgot-password.card.user"/>
                @endif

                @if ($rateLimitTime > 1)
                    <div wire:poll.1s="setRateLimit">
                        <x-alert color="error">
                            <i class="icon-triangle-alert"></i>
                            {{ __('auth.throttle', ['seconds' => $rateLimitTime]) }}
                        </x-alert>
                    </div>

                    <x-view-integration name="auth.forgot-password.card.rate_limit"/>
                @endif

                @if($passwordResetToken)
                    <form class="space-y-4" wire:submit="resetPassword">
                        <x-password wire:model="password" required>
                            {{ __('auth::forgot-password.password') }}
                        </x-password>

                        <x-password wire:model="passwordConfirmation" required>
                            {{ __('auth::forgot-password.password_confirmation') }}
                        </x-password>

                        <x-view-integration name="auth.reset-password.card.form"/>

                        <x-button class="w-full" type="submit" loading="resetPassword">
                            {{ __('auth::forgot-password.buttons.reset_password') }}
                        </x-button>
                        <x-view-integration name="auth.reset-password.card.form.buttons"/>

                    </form>
                @else
                    <form class="space-y-4" wire:submit="sendResetLink">
                        <x-input wire:model="email" wire:blur="checkIfUserExists($event.target.value)" required
                                 autofocus>
                            {{ __('auth::forgot-password.email') }}
                        </x-input>

                        <x-view-integration name="auth.forgot-password.card.form"/>

                        @if(settings()->isTrue('auth.forgot_password.enable.captcha', config('auth.forgot_password.captcha')))
                            <div class="gap-3 lg:flex space-y-3">
                                <img src="{{ captcha_src('inverse') }}" class="rounded-lg lg:w-1/2 w-full"
                                     alt="Captcha">

                                <x-input wire:model="captcha" required>
                                    {{ __('auth::forgot-password.captcha') }}
                                </x-input>

                                <x-view-integration name="auth.forgot-password.card.captcha"/>
                            </div>
                        @endif

                        <x-button class="w-full" type="submit" loading="sendResetLink">
                            {{ __('auth::forgot-password.buttons.send_reset_link') }}
                        </x-button>
                        <x-view-integration name="auth.forgot-password.card.form.buttons"/>

                    </form>
                @endif

                <x-view-integration name="auth.forgot-password.card.end"/>

            </x-card>
        </div>

        @if($unsplash['error'] == null)
            <div class="absolute bottom-0 left-0 p-4 text-white">
                <span class="text-sm" wire:ignore>
                    <a href="{{ $unsplash['photo'] }}">{{ __('auth::forgot-password.photo') }}</a>,
                    <a href="{{ $unsplash['authorURL'] }}">{{ $unsplash['author'] }}</a>,
                    <a href="{{ $unsplash['utm'] }}">Unsplash</a>
                </span>
            </div>
        @endif

        <div class="absolute bottom-6 left-0 p-4 sm:bottom-0 sm:right-0 sm:left-auto">
            <x-select class="max-w-sm" wire:change="changeLanguage($event.target.value)">
                <option value="en" @if(app()->getLocale() == 'en') selected @endif>English</option>
                <option value="de" @if(app()->getLocale() == 'de') selected @endif>Deutsch</option>

                <x-view-integration name="auth.forgot-password.language"/>

            </x-select>
        </div>
    </div>
</div>

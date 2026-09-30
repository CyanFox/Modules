<div>
    <div class="flex relative min-h-screen">
        @hook('auth.forgot_password.unsplash.css')
        <div class="absolute inset-0 z-[-1]" style="{{ $unsplash['css'] }}"></div>
        @endhook
        <div class="justify-center m-auto">
            <div class="mb-4">
                @hook('auth.forgot_password.logo')
                <img src="{{ settings('app.logo') }}" alt="Logo"
                     class="mx-auto" style="{{ settings('app.logo.css') }}">
                @endhook
            </div>

            @hook('auth.forgot_password.card')
            <x-card class="md:w-sm space-y-4">

            </x-card>
            @endhook
        </div>

        @if($unsplash['error'] == null)
            @hook('auth.forgot_password.unsplash.utm')
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

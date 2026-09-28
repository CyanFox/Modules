<div>
    <div class="flex relative min-h-screen">
        @hook('auth.login.unsplash.css')
        <div class="absolute inset-0 z-[-1]" style="{{ $unsplash['css'] }}"></div>
        @endhook
        <div class="justify-center m-auto">
            <div class="mb-4">
                @hook('auth.login.logo')
                <img src="{{ settings('app.logo') }}" alt="Logo"
                     class="mx-auto" style="{{ settings('app.logo.css') }}">
                @endhook
            </div>

            @hook('auth.login.card')
            <x-card>
                <x-input label="Username" required/>
                <x-input label="Password" type="password" required/>
                <x-button class="btn-primary">
                    Login
                </x-button>
            </x-card>
            @endhook
        </div>

        @if($unsplash['error'] == null)
            @hook('auth.login.unsplash.utm')
            <div class="absolute bottom-0 left-0 p-4 text-white">
                <span class="text-sm" wire:ignore>
                    <a href="{{ $unsplash['photo'] }}">{{ __('auth::login.photo') }}</a>,
                    <a href="{{ $unsplash['authorURL'] }}">{{ $unsplash['author'] }}</a>,
                    <a href="{{ $unsplash['utm'] }}">Unsplash</a>
                </span>
            </div>
            @endhook
        @endif
    </div>
</div>

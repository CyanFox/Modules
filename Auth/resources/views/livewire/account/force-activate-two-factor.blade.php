<div>
    <div class="flex relative min-h-screen">
        <div class="absolute inset-0 z-[-1]" style="{{ $unsplash['css'] }}"></div>
        <div class="justify-center m-auto md:w-1/3 max-w-md w-full">
            <div class="mb-4">
                <img src="{{ settings('internal.app.logo', config('settings.logo_path')) }}" alt="Logo"
                     class="{{ settings('auth.logo_size', config('auth.logo_size')) }} mx-auto">
                <x-view-integration name="auth.force.change-password.logo"/>
            </div>

            <x-card class="space-y-4 mx-auto">

                @if(empty($recoveryCodes))
                    <div class="flex flex-col items-center my-4">
                        <img src="data:image/svg+xml;base64,{{ auth()->user()->getTwoFactorImage() }}" alt="QR Code"
                             class="w-32 h-32 mx-auto mb-2 p-0.5 bg-white"/>
                        <p>{{ decrypt(auth()->user()->two_factor_secret) }}</p>
                    </div>

                    <form wire:submit="activateTwoFA" class="space-y-4">
                        <x-password wire:model="currentPassword" label="{{ __('auth::force.activate_two_factor.current_password') }}" required/>

                        <x-input wire:model="twoFactorCode"
                                 label="{{ __('auth::force.activate_two_factor.two_fa_code') }}" required/>

                        <x-button type="submit" loading="activateTwoFA" class="w-full">
                            {{ __('auth::force.activate_two_factor.buttons.activate_two_fa') }}
                        </x-button>
                    </form>
                @else
                    <div class="space-y-4">
                        <div class="px-4 mt-3 text-center">
                            {{ __('auth::force.activate_two_factor.recovery_codes') }}
                        </div>

                        <div class="flex flex-col items-center my-4">
                            @foreach($recoveryCodes as $recoveryCode)
                                <p class="mb-2">{{ $recoveryCode }}</p>
                            @endforeach
                        </div>


                        <div class="grid md:grid-cols-2 gap-2">
                            <x-button wire:click="downloadRecoveryCodes" loading="downloadRecoveryCodes"
                                      class="w-full">
                                {{ __('auth::force.activate_two_factor.buttons.download_recovery_codes') }}
                            </x-button>
                            <x-button wire:click="finish" loading="finish"
                                      class="w-full">
                                {{ __('auth::force.activate_two_factor.buttons.finish') }}
                            </x-button>
                        </div>
                    </div>
                @endif

                <x-view-integration name="auth.force.change-password.card.end"/>

            </x-card>
        </div>

        @if($unsplash['error'] == null)
            <div class="absolute bottom-0 left-0 p-4 text-white">
                <span class="text-sm" wire:ignore>
                    <a href="{{ $unsplash['photo'] }}">{{ __('auth::force.photo') }}</a>,
                    <a href="{{ $unsplash['authorURL'] }}">{{ $unsplash['author'] }}</a>,
                    <a href="{{ $unsplash['utm'] }}">Unsplash</a>
                </span>
            </div>
        @endif
    </div>
</div>

<div>
    <div class="flex min-h-screen">
        <div class="absolute inset-0 z-[-1]" style="{{ $unsplash['css'] }}"></div>
        <div class="justify-center m-auto md:w-1/3 max-w-lg">
            <x-card>
                <div class="flex items-center justify-center">
                    <img class="size-24" src="{{ asset(setting('settings.logo_path')) }}" alt="Logo">
                    <x-view-integration name="authmodule_force_activate_two_factor_logo"/>
                </div>
                <div class="space-y-4">
                    <x-view-integration name="authmodule_force_activate_two_factor_top"/>

                    <form wire:submit="activateTwoFactor">
                        @csrf

                        <div class="mb-3">
                            <div class="flex flex-col items-center">
                                <img
                                    src="data:image/svg+xml;base64,{{ user()->getUser(auth()->user())->getTwoFactorManager()->getTwoFactorImage() }}"
                                    alt="Two Factor Image"
                                    class="border-4 border-white mb-2">
                                <p>{{ decrypt(auth()->user()->two_factor_secret) }}</p>
                            </div>

                            <div class="space-y-4 mb-3">
                                <x-password label="{{ __('authmodule::messages.password') }} *"
                                            wire:model="password"/>

                                <x-input label="{{ __('authmodule::account.force_actions.activate_two_factor.two_factor_code')}} *"
                                         wire:model="twoFactorCode"/>
                            </div>

                            <x-view-integration name="authmodule_force_activate_two_factor_form"/>
                        </div>

                        <x-button class="mt-3 w-full" loading="activateTwoFactor">
                            {{ __('authmodule::account.force_actions.activate_two_factor.buttons.activate_two_factor') }}
                        </x-button>
                    </form>


                    <x-view-integration name="authmodule_force_activate_two_factor_bottom"/>

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

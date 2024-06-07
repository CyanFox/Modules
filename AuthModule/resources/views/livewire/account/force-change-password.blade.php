<div>
    <div class="flex min-h-screen">
        <div class="absolute inset-0 z-[-1]" style="{{ $unsplash['css'] }}"></div>
        <div class="justify-center m-auto md:w-1/3 max-w-lg">
            <x-card>
                <div class="flex items-center justify-center">
                    <img class="size-24" src="{{ asset(setting('settings.logo_path')) }}" alt="Logo">
                    <x-view-integration name="authmodule_force_change_password_logo"/>
                </div>
                <div class="space-y-4">
                    <x-view-integration name="authmodule_force_change_password_top"/>

                    <form wire:submit="updatePassword">
                        @csrf

                        <div class="mb-3">
                            @if(auth()->user()->password !== null)
                                <x-password label="{{ __('authmodule::account.overview.password.current_password') }} *"
                                            wire:model="currentPassword"/>
                            @endif
                            <div class="grid md:grid-cols-2 grid-cols-1 gap-4 @if(auth()->user()->password !== null) mt-4 @endif">
                                <x-password label="{{ __('authmodule::messages.new_password') }} *"
                                            wire:model="newPassword"/>
                                <x-password
                                    label="{{ __('authmodule::account.force_actions.change_password.new_password_confirm') }} *"
                                    wire:model="newPasswordConfirmation"/>
                            </div>
                        </div>
                        <x-view-integration name="authmodule_force_change_password_form"/>

                        <x-button class="mt-3 w-full" loading="updatePassword">
                            {{ __('authmodule::account.force_actions.change_password.buttons.change_password') }}
                        </x-button>
                    </form>


                    <x-view-integration name="authmodule_force_change_password_bottom"/>

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

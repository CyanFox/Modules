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
                <form class="space-y-4" wire:submit="changePassword">
                    <x-password wire:model="currentPassword" label="{{ __('auth::force.change_password.current_password') }}" required/>

                    <div class="grid md:grid-cols-2 gap-4">
                        <x-password wire:model="newPassword" label="{{ __('auth::force.change_password.new_password') }}" required/>
                        <x-password wire:model="confirmPassword" label="{{ __('auth::force.change_password.confirm_password') }}" required/>
                    </div>

                    <x-view-integration name="auth.force.change-password.card.form"/>

                    <x-button class="w-full" type="submit" loading="changePassword">
                        {{ __('auth::force.change_password.buttons.change_password') }}
                    </x-button>

                    <x-view-integration name="auth.force.change-password.card.form.buttons"/>

                </form>

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

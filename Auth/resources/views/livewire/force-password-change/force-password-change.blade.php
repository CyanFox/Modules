<div>
    <div class="relative flex min-h-dvh flex-col">
        @hook('auth.force_change_password.unsplash.css')
        <div class="absolute inset-0 z-[-1]" style="{{ data_get($unsplash, 'css') }}"></div>
        @endhook
        <main class="flex flex-1 items-center justify-center px-4 py-8">
            <div class="w-full sm:w-md" wire:transition.navigate>
                <div class="mb-4">
                    @hook('auth.force_change_password.logo')
                    <img src="{{ settings('app.logo') }}" alt="Logo"
                         class="mx-auto" style="{{ settings('auth.logo.css') }}">
                    @endhook
                </div>

                @hook('auth.force_change_password.card')
                <x-card class="w-full space-y-4">
                    <div class="rounded-radius border border-on-surface dark:border-on-surface-dark/50"
                         wire:transition>
                        <div class="flex p-1 relative">
                            <img
                                src="{{ auth()->user()->getAvatar() }}"
                                alt="Avatar"
                                class="rounded-full w-8 h-8 m-1">
                            <p class="absolute top-1/2 left-1/2 translate-x-[-50%] translate-y-[-50%]">{{ auth()->user()->username }}</p>
                        </div>
                    </div>

                    @hook('auth.force_change_password.form')
                    <form wire:submit="changePassword" class="space-y-4">
                        @if(filled(auth()->user()->password))
                            <x-password wire:model="currentPassword"
                                        :label="__('auth::force.change_password.current_password')" required/>
                        @endif

                        <div class="grid md:grid-cols-2 gap-4">
                            <x-password wire:model="newPassword" :label="__('auth::force.change_password.new_password')"
                                        required/>
                            <x-password wire:model="confirmNewPassword"
                                        :label="__('auth::force.change_password.confirm_new_password')" required/>
                        </div>

                        <x-divider/>

                        <x-button class="w-full" type="submit" loading="changePassword">
                            {{ __('auth::force.change_password.buttons.change_password') }}
                        </x-button>
                        @shook('s.auth.force_change_password.buttons')
                    </form>
                    @endhook
                </x-card>
                @endhook
            </div>
        </main>

        <footer class="relative flex gap-2 p-4 flex-row items-end justify-between">
            @if(data_get($unsplash, 'error') == null)
                @hook('auth.force_change_password.unsplash.utm')
                <div class="text-white">
                    <span class="text-sm" wire:ignore>
                        <a href="{{ data_get($unsplash, 'photo') }}">{{ __('auth::force.photo') }}</a>,
                        <a href="{{ data_get($unsplash, 'authorURL') }}">{{ data_get($unsplash, 'author') }}</a>,
                        <a href="{{ data_get($unsplash, 'utm') }}">Unsplash</a>
                    </span>
                </div>
                @endhook
            @endif
        </footer>
    </div>
</div>

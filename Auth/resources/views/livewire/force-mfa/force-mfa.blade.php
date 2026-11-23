<div>
    <div class="relative flex min-h-dvh flex-col">
        @hook('auth.force_mfa.unsplash.css')
        <div class="absolute inset-0 z-[-1]" style="{{ data_get($unsplash, 'css') }}"></div>
        @endhook
        <main class="flex flex-1 items-center justify-center px-4 py-8">
            <div class="w-full sm:w-md" wire:transition.navigate>
                <div class="mb-4">
                    @hook('auth.force_mfa.logo')
                    <img src="{{ settings('app.logo') }}" alt="Logo"
                         class="mx-auto" style="{{ settings('auth.logo.css') }}">
                    @endhook
                </div>

                @hook('auth.force_mfa.card')
                <x-card class="w-full space-y-4">
                    @if($recoveryCodes)
                        <span>
                            {{ __('auth::force.mfa.recovery_codes_description') }}
                        </span>

                        <div class="flex flex-col items-center my-4">
                            @foreach($recoveryCodes as $recoveryCode)
                                <p class="mb-2">{{ $recoveryCode }}</p>
                            @endforeach
                        </div>

                        <div class="flex flex-wrap gap-2">
                            <x-button type="submit" loading="regenerateCodes" wire:click="regenerateCodes"
                                      color="warning">
                                {{ __('auth::force.mfa.buttons.regenerate') }}
                            </x-button>
                            <x-button type="submit" loading="downloadCodes" wire:click="downloadCodes">
                                {{ __('auth::force.mfa.buttons.download') }}
                            </x-button>
                        </div>
                        <x-button type="submit" wire:click="finish" loading="finish">
                            {{ __('auth::force.mfa.buttons.finish') }}
                        </x-button>

                        @shook('s.auth.force_mfa.recovery_codes.buttons')
                    @else
                        <div class="flex flex-col items-center">
                            <x-qr-code
                                data="{{ $mfaSecret }}"
                                error-correction="H"
                                size="300" margin="2"/>

                            <span>{{ $mfaSecret }}</span>
                        </div>

                        <form wire:submit="enableMfa" class="space-y-4">
                            @if(filled(auth()->user()->password))
                                <x-password wire:model="currentPassword"
                                            :label="__('auth::force.change_password.current_password')" autofocus
                                            required/>
                            @endif

                            <x-input wire:model="code"
                                     label="{{ __('auth::force.mfa.code') }}" required/>

                            <x-divider/>

                            <x-button type="submit" loading="enableMfa">
                                {{ __('auth::force.mfa.buttons.enable') }}
                            </x-button>
                            @shook('s.auth.force_mfa.buttons')
                        </form>
                    @endif
                </x-card>
                @endhook
            </div>
        </main>

        <footer class="relative flex gap-2 p-4 flex-row items-end justify-between">
            @if(data_get($unsplash, 'error') == null)
                @hook('auth.force_mfa.unsplash.utm')
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

<div>
    <x-modal.header>
        {{ $title }}
    </x-modal.header>

    <div class="px-4 mt-3">
        {{ $description }}
    </div>

    <form wire:submit="confirmPassword">
        <div class="p-4">
            <x-password wire:model="password" label="{{ __('core::modals.confirm_password.password') }}" autofocus
                        required/>

            @if ($hasPasskeys)
                <div
                    x-data="{ confirmingPasskey: false }"
                    x-on:passkey-confirmed.window="confirmingPasskey = false"
                >
                    <div class="space-y-3 mt-4">
                        <x-button
                            variant="outline"
                            type="button"
                            x-on:click="
                        confirmingPasskey = true;
                        (async () => {
                            try {
                                const optionsJSON = await $wire.generatePasskeyAuthenticationOptions();
                                const options = JSON.parse(optionsJSON);
                                const response = await startAuthentication({ optionsJSON: options });
                                await $wire.confirmPasskey(JSON.stringify(response));
                                confirmingPasskey = false;
                            } catch (error) {
                                confirmingPasskey = false;
                            }
                        })()
                    "
                            x-bind:disabled="confirmingPasskey"
                        >
                            {{ __('passkeys::passkeys.authenticate_using_passkey') }}
                        </x-button>

                        @error('passkey')
                        <div class="text-sm text-red-600">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            @endif
        </div>

        <x-modal.footer>
            <x-button type="submit" loading="confirmPassword">
                {{ __('messages.buttons.confirm') }}
            </x-button>
        </x-modal.footer>
    </form>
</div>

<x-modal center wire="activateTwoFactorModal">
    <form wire:submit="activateTwoFactor">
        @csrf

        <x-view-integration name="authmodule.activate_two_factor_modal.top"/>

        <div class="flex flex-col items-center">
            <img
                src="data:image/svg+xml;base64,{{ user()->getUser(auth()->user())->getTwoFactorManager()->getTwoFactorImage() }}"
                alt="Two Factor Image"
                class="border-4 border-white mb-2">
            <p>{{ decrypt(auth()->user()->two_factor_secret) }}</p>
        </div>

        <div class="space-y-4 mb-3">
            <x-password :label="__('authmodule::messages.password') . ' *'"
                        wire:model="activateTwoFactorPassword"/>

            <x-input :label="__('authmodule::account.overview.actions.modals.activate_two_factor.two_factor_code') . ' *'"
                     wire:model="activateTwoFactorCode"/>
        </div>

        <x-view-integration name="authmodule.activate_two_factor_modal.form"/>

        <x-divider/>

        <div class="mt-3 md:flex md:justify-between md:space-y-0 space-y-3 gap-3">
            <x-button class="w-full" type="button" color="gray" wire:click="$toggle('activateTwoFactorModal')">
                {{ __('messages.buttons.cancel') }}
            </x-button>
            <x-button class="w-full" type="submit">
                {{ __('authmodule::account.overview.actions.modals.activate_two_factor.buttons.activate_two_factor') }}
            </x-button>
        </div>

        <x-view-integration name="authmodule.activate_two_factor_modal.bottom"/>
    </form>
</x-modal>

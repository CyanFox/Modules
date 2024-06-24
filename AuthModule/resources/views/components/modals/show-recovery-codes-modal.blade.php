<x-modal center wire="showRecoveryCodesModal">
    <div class="text-center">
        <h2 class="text-2xl font-bold mb-4">{{ __('authmodule::account.overview.actions.modals.show_recovery_codes.title') }}</h2>
        <p class="mb-3">{{ __('authmodule::account.overview.actions.modals.show_recovery_codes.description') }}</p>
    </div>
    <x-view-integration name="authmodule.show_recovery_codes_modal.top"/>

    <div class="text-center space-y-4 mb-3">
        @foreach($this->showRecoveryCodes as $recoveryCode)
            <p class="mb-3">{{ $recoveryCode }}</p>
        @endforeach
    </div>

    <x-view-integration name="authmodule.show_recovery_codes_modal.middle"/>

    <x-divider/>

    <div class="mt-3 md:flex md:justify-between md:space-y-0 space-y-3 gap-3">
        <x-button class="w-full" color="gray" wire:click="$toggle('showRecoveryCodesModal')">
            {{ __('messages.buttons.close') }}
        </x-button>
        <x-button class="w-full" color="yellow" wire:click="regenerateRecoveryCodes" loading="regenerateRecoveryCodes">
            {{ __('authmodule::account.overview.actions.modals.show_recovery_codes.buttons.regenerate') }}
        </x-button>
        <x-button class="w-full" color="green" wire:click="downloadRecoveryCodes" loading="downloadRecoveryCodes">
            {{ __('authmodule::account.overview.actions.modals.show_recovery_codes.buttons.download') }}
        </x-button>
    </div>

    <x-view-integration name="authmodule.show_recovery_codes_modal.bottom"/>
</x-modal>

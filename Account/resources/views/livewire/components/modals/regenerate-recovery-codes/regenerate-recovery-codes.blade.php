<div>
    <x-modal.header>
        {{ __('account::modals.regenerate_recovery_codes.title') }}
    </x-modal.header>

    <div class="p-4">
        <span>
            {{ __('account::modals.regenerate_recovery_codes.description') }}
        </span>

        <div class="flex flex-col items-center mt-4">
            @foreach($recoveryCodes as $recoveryCode)
                <p class="mb-2">{{ $recoveryCode }}</p>
            @endforeach
        </div>
    </div>

    <x-modal.footer>
        <x-button type="submit" loading="regenerateCodes" wire:click="regenerateCodes" color="warning">
            {{ __('account::modals.regenerate_recovery_codes.buttons.regenerate') }}
        </x-button>
        <x-button type="submit" loading="downloadCodes" wire:click="downloadCodes">
            {{ __('account::modals.regenerate_recovery_codes.buttons.download') }}
        </x-button>
    </x-modal.footer>
</div>

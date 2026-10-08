<div>
    @if($recoveryCodes)
        <x-modal.header>
            {{ __('account::modals.enable_mfa.recovery_codes') }}
        </x-modal.header>

        <div class="p-4">
            <span>
                {{ __('account::modals.enable_mfa.recovery_codes_description') }}
            </span>

            <div class="flex flex-col items-center my-4">
                @foreach($recoveryCodes as $recoveryCode)
                    <p class="mb-2">{{ $recoveryCode }}</p>
                @endforeach
            </div>
        </div>

        <x-modal.footer>
            <x-button type="submit" loading="regenerateCodes" wire:click="regenerateCodes" color="warning">
                {{ __('account::modals.enable_mfa.buttons.regenerate') }}
            </x-button>
            <x-button type="submit" loading="downloadCodes" wire:click="downloadCodes">
                {{ __('account::modals.enable_mfa.buttons.download') }}
            </x-button>
        </x-modal.footer>
    @else
        <x-modal.header>
            {{ __('account::modals.enable_mfa.title') }}
        </x-modal.header>

        <div class="flex flex-col items-center p-4">
            <x-qr-code
                data="{{ $mfaSecret }}"
                error-correction="H"
                size="300" margin="2"/>

            <span>{{ $mfaSecret }}</span>
        </div>

        <form wire:submit="enableMfa">
            <div class="p-4">
                <x-input wire:model="code"
                         label="{{ __('account::modals.enable_mfa.code') }}" autofocus required/>
            </div>

            <x-modal.footer>
                <x-button type="submit" loading="enableMfa">
                    {{ __('account::modals.enable_mfa.buttons.enable') }}
                </x-button>
            </x-modal.footer>
        </form>
    @endif
</div>

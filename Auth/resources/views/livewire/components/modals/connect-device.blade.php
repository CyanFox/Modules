<div class="sm:w-96">
    @if($this->hasPasswordConfirmedSession())
        <x-modal.header>
            {{ __('auth::profile.connected_devices.modals.connect_device.title') }}
        </x-modal.header>

        <div class="px-4 mt-3 text-center">
            {{ __('auth::profile.connected_devices.modals.connect_device.description') }}
        </div>

        @if($key)
            <div class="flex justify-center mt-3">
            <span
                class="p-0.5 bg-white">{!! \SimpleSoftwareIO\QrCode\Facades\QrCode::size(100)->generate($key ?? '-') !!}</span>
            </div>
        @endif

        <div class="p-4 space-y-4">
            <x-input
                wire:model="key"
                disabled
                label="{{ __('auth::profile.connected_devices.modals.connect_device.key') }}"/>
        </div>

        <x-modal.footer>
            <x-button wire:click="closeModal" loading="closeModal" class="w-full">
                {{ __('messages.buttons.close') }}
            </x-button>
        </x-modal.footer>
    @endif
</div>

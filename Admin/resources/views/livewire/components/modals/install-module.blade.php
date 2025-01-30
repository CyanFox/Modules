<div class="sm:w-96">
    <x-modal.header>
        {{ __('admin::modules.install_module.title') }}
    </x-modal.header>

    <form wire:submit="installModule" wire:ignore>
        <div class="space-y-4 p-4">
            <x-file accept=".zip" wire:model="moduleFile">{{ __('admin::modules.install_module.file') }}</x-file>

            <x-divider/>
            <x-input type="url" wire:model="moduleUrl">{{ __('admin::modules.install_module.url') }}</x-input>
        </div>

        <x-modal.footer>
            <x-button wire:click="closeModal" loading="closeModal" class="w-full lg:w-1/2" variant="outline">
                {{ __('messages.buttons.cancel') }}
            </x-button>
            <x-button type="submit" loading="installModule" class="w-full lg:w-1/2">
                {{ __('admin::modules.install_module.buttons.install_module') }}
            </x-button>
        </x-modal.footer>
    </form>
</div>

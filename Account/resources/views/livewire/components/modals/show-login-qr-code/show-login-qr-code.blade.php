<div>
    <x-modal.header>
        Login QR Code
    </x-modal.header>

    <form wire:submit="createApiKey">
        <div class="p-4 space-y-4">
            <x-input
                wire:model="name"
                label="Name"
                required/>
        </div>

        <x-modal.footer>
            <div class="w-full">
                <x-button wire:click="closeModal" loading="closeModal" class="w-full" color="secondary">
                    Cancel
                </x-button>
            </div>
            <div class="w-full">
                <x-button type="submit" loading="createApiKey" class="w-full">
                    Create
                </x-button>
            </div>
        </x-modal.footer>
    </form>
</div>

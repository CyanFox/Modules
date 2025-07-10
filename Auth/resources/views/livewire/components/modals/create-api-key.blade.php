<div class="sm:w-96">
    @if($this->hasPasswordConfirmedSession())
        <x-modal.header>
            {{ __('auth::profile.api_keys.modals.create_api_key.title') }}
        </x-modal.header>

        @if($key)
            <div class="px-4 mt-3 text-center">
                {{ __('auth::profile.api_keys.modals.create_api_key.generated_key_description') }}
            </div>

            <div class="p-4 space-y-4">
                <x-input
                    wire:model="key"
                    disabled
                    label="{{ __('auth::profile.api_keys.modals.create_api_key.generated_key') }}"/>
            </div>

            <x-modal.footer>
                <x-button wire:click="closeModal" loading="closeModal" class="w-full lg:w-1/2">
                    {{ __('messages.buttons.close') }}
                </x-button>
            </x-modal.footer>
        @else
            <form wire:submit="createApiKey">
                <div class="p-4 space-y-4">
                    <x-input
                        wire:model="name"
                        label="{{ __('auth::profile.api_keys.name') }}"
                        required/>

                    <x-select.multiple
                        label="{{ __('auth::profile.api_keys.permissions') }}"
                        :options="
                        auth()->user()->getAllPermissions()
                            ->map(fn($permission) => [
                                'value' => $permission->id,
                                'label' => $permission->name
                            ])
                            ->toArray()
                    "
                        wire:model="permissions"
                        required
                    />
                </div>

                <x-modal.footer>
                    <x-button wire:click="closeModal" loading="closeModal" class="w-full lg:w-1/2" color="secondary">
                        {{ __('messages.buttons.cancel') }}
                    </x-button>
                    <x-button type="submit" loading="createApiKey" class="w-full lg:w-1/2">
                        {{ __('auth::profile.api_keys.modals.create_api_key.buttons.create_api_key') }}
                    </x-button>
                </x-modal.footer>
            </form>
        @endif
    @endif
</div>

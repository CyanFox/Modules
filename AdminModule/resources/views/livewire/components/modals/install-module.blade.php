<x-modal center wire="installModal">
    <div class="text-center">
        <h2 class="text-2xl font-bold mb-4">{{ __('adminmodule::modules.install_module.title') }}</h2>
        <p class="mb-3">{{ __('adminmodule::modules.install_module.description') }}</p>

        <x-view-integration name="authmodule.activate_two_factor_modal.title"/>
    </div>

    <x-view-integration name="authmodule.activate_two_factor_modal.header"/>

    <form wire:submit="installModule">
        @csrf

        <div class="space-y-4 mb-3">
            <x-upload wire:model="moduleFile"></x-upload>

            <div class="flex justify-center">
                <span class="text-sm mt-3">
                    {{ __('adminmodule::modules.install_module.or_from_url') }}
                </span>
            </div>

            <x-input
                :label="__('adminmodule::modules.install_module.url')"
                wire:model="moduleUrl"/>
        </div>

        <x-view-integration name="authmodule.activate_two_factor_modal.form"/>

        <x-divider/>

        <div class="mt-3 md:flex md:justify-between md:space-y-0 space-y-3 gap-3">
            <x-button class="w-full" type="button" color="gray" wire:click="$toggle('installModal')">
                {{ __('messages.buttons.cancel') }}
            </x-button>
            <x-button class="w-full" type="submit" loading="installModule">
                {{ __('adminmodule::modules.install_module.buttons.install_module') }}
            </x-button>

            <x-view-integration name="authmodule.activate_two_factor_modal.buttons"/>
        </div>

        <x-view-integration name="authmodule.activate_two_factor_modal.footer"/>
    </form>
</x-modal>

<x-modal center wire="showChangeAvatarModal">
    <div class="text-center">
        <h2 class="text-2xl font-bold mb-4">{{ __('authmodule::account.change_avatar.title') }}</h2>
        <p class="mb-3">{{ __('authmodule::account.change_avatar.description') }}</p>
    </div>

    <form wire:submit="changeAvatar">
        @csrf

        <div class="space-y-4 mb-3">

            <x-upload wire:model="avatarFile"></x-upload>

            <div class="flex justify-center">
                <span class="text-sm mt-3">
                    {{ __('authmodule::account.change_avatar.or_from_url') }}
                </span>
            </div>

            <x-input
                    :label="__('authmodule::account.change_avatar.url')"
                    wire:model="avatarUrl"/>
        </div>

        <x-view-integration name="authmodule.activate_two_factor_modal.form"/>

        <x-divider/>

        <div class="mt-3 md:flex md:justify-between md:space-y-0 space-y-3 gap-3">
            <x-button class="w-full" type="button" color="gray" wire:click="$toggle('showChangeAvatarModal')">
                {{ __('messages.buttons.cancel') }}
            </x-button>
            <x-button class="w-full" type="button" color="yellow" wire:click="resetAvatar" loading>
                {{ __('authmodule::account.change_avatar.buttons.reset_avatar') }}
            </x-button>
            <x-button class="w-full" type="submit" loading="installModule">
                {{ __('authmodule::account.change_avatar.buttons.change_avatar') }}
            </x-button>
        </div>

        <x-view-integration name="authmodule.activate_two_factor_modal.bottom"/>
    </form>
</x-modal>

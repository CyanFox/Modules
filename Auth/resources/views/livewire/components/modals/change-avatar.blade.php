<div class="sm:w-96">
    <x-modal.header>
        {{ __('auth::profile.modals.change_avatar.title') }}
    </x-modal.header>


    <div class="px-4 mt-3">
        {{ __('auth::profile.modals.change_avatar.description') }}
    </div>

    <form wire:submit="changeAvatar" wire:ignore>
        <div class="space-y-4 p-4">
            <x-file wire:model="avatar">{{ __('auth::profile.modals.change_avatar.avatar') }}</x-file>

            <x-input type="url" wire:model="avatarUrl">{{ __('auth::profile.modals.change_avatar.avatar_url') }}</x-input>
        </div>

        <x-modal.footer>
            <x-button type="button" wire:click="resetAvatar" loading="resetAvatar" color="warning" class="w-full lg:w-1/2">
                {{ __('auth::profile.modals.change_avatar.buttons.reset') }}
            </x-button>
            <x-button type="submit" loading="changeAvatar" class="w-full lg:w-1/2">
                {{ __('messages.buttons.confirm') }}
            </x-button>
        </x-modal.footer>
    </form>
</div>

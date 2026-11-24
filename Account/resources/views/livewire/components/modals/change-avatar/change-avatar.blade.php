<div>
    <x-modal.header>
        {{ __('account::modals.change_avatar.title') }}
    </x-modal.header>

    <form wire:submit="changeAvatar">
        <div class="p-4">
            <div class="grid md:grid-cols-2 mb-4 gap-4">
                <div class="flex justify-center items-center">
                    <img src="{{ $currentAvatar }}" alt="Avatar" class="size-36 rounded-full">
                </div>
                <div class="space-y-4">
                    <x-input wire:model.live.debounce.250ms="customAvatarUrl" type="url"
                             :label="__('account::modals.change_avatar.custom_avatar_url')"/>
                    <x-file wire:model="avatar" :label="__('account::modals.change_avatar.avatar')"/>
                </div>
            </div>
        </div>

        <x-modal.footer>
            <x-button wire:click="resetAvatar" loading="resetAvatar" color="warning">
                {{ __('account::modals.change_avatar.buttons.reset') }}
            </x-button>
            <x-button type="submit" loading="changeAvatar">
                {{ __('account::modals.change_avatar.buttons.change') }}
            </x-button>
        </x-modal.footer>
    </form>
</div>

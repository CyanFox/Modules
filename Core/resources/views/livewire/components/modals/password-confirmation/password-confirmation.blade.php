<div>
    <x-modal.header>
        {{ $title }}
    </x-modal.header>

    <div class="px-4 mt-3">
        {{ $description }}
    </div>

    <form wire:submit="confirmPassword">
        <div class="p-4">
            <x-password wire:model="password" label="{{ __('core::modals.confirm_password.password') }}" autofocus
                        required/>
        </div>

        <x-modal.footer>
            <x-button type="submit" loading="confirmPassword">
                {{ __('messages.buttons.confirm') }}
            </x-button>
        </x-modal.footer>
    </form>
</div>

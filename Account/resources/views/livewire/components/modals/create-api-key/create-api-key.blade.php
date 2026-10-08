<div>
    <x-modal.header>
        {{ __('account::modals.create_api_key.title') }}
    </x-modal.header>

    @if($token)
        <div class="p-4">
            <span>{{ __('account::modals.create_api_key.token_description') }}</span>
            <div class="mt-4">
                <x-input wire:model="token" readonly/>
            </div>
        </div>

        <x-modal.footer>
            <x-button wire:click="closeModal" loading="closeModal">
                {{ __('messages.buttons.close') }}
            </x-button>
        </x-modal.footer>
    @else
        <form wire:submit="createApiKey">
            <div class="p-4 space-y-4">
                <x-input wire:model="name" :label="__('account::modals.create_api_key.name')" autofocus required/>
                <x-select.multiple wire:model="permissions" :label="__('account::modals.create_api_key.permissions')"
                                   required>
                    @foreach(auth()->user()->permissions as $permission)
                        <option value="{{ $permission->name }}">{{ $permission->name }}</option>
                    @endforeach
                </x-select.multiple>
            </div>

            <x-modal.footer>
                <x-button type="submit" loading="createApiKey">
                    {{ __('account::modals.create_api_key.buttons.create') }}
                </x-button>
            </x-modal.footer>
        </form>
    @endif
</div>

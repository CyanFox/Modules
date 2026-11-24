<div>
    <div class="mt-2">
        <form id="passkeyForm" wire:submit="validatePasskeyProperties" class="flex flex-col gap-4">
            <x-input wire:model="name" label="{{ __('passkeys::passkeys.name') }}" required/>

            <x-button type="submit" class="md:w-fit">
                {{ __('account::account.buttons.create_passkey') }}
            </x-button>
        </form>
    </div>

    <x-divider/>

    <div class="mt-6">
        <ul class="space-y-4 overflow-x-auto">
            <x-table>
                <x-table.header>
                    <x-table.header.item>
                        {{ __('account::account.name') }}
                    </x-table.header.item>
                    <x-table.header.item>
                        {{ __('account::account.last_used') }}
                    </x-table.header.item>
                    <x-table.header.item>
                        {{ __('messages.tables.created_at') }}
                    </x-table.header.item>
                    <x-table.header.item>
                        {{ __('messages.tables.actions') }}
                    </x-table.header.item>
                </x-table.header>
                <x-table.body>
                    @foreach($passkeys as $passkey)
                        <x-table.body.row>
                            <x-table.body.item>
                                {{ $passkey->name }}
                            </x-table.body.item>
                            <x-table.body.item>
                                <x-core::human-date :date="$passkey->last_used_at"
                                                    :fallback="__('passkeys::passkeys.not_used_yet')"/>
                            </x-table.body.item>
                            <x-table.body.item>
                                <x-core::human-date :date="$passkey->created_at"/>
                            </x-table.body.item>
                            <x-table.body.item>
                                <x-button.floating wire:click="deletePasskey({{ $passkey->id }})" size="sm"
                                                   color="danger" loading="deletePasskey">
                                    <i class="icon-trash"></i>
                                </x-button.floating>
                            </x-table.body.item>
                        </x-table.body.row>
                    @endforeach
                </x-table.body>
            </x-table>
        </ul>
    </div>
</div>

@include('account::livewire.components.passkeys.partials.createScript')

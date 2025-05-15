<div>
    <div class="mt-2">
        <form id="passkeyForm" wire:submit="validatePasskeyProperties" class="flex flex-col gap-4">
            <x-input wire:model="name" label="{{ __('passkeys::passkeys.name') }}" class="min-w-full" />

            <x-button type="submit" class="w-fit">
                {{ __('messages.buttons.save') }}
            </x-button>
        </form>
    </div>

    <x-divider/>

    <div class="mt-6">
        <ul class="space-y-4 overflow-x-auto">
            <x-table>
                <x-table.header>
                    <x-table.header.item>
                        {{ __('auth::profile.passkeys.name') }}
                    </x-table.header.item>
                    <x-table.header.item>
                        {{ __('auth::profile.passkeys.last_used') }}
                    </x-table.header.item>
                    <x-table.header.item>
                        {{ __('messages.tables.actions') }}
                    </x-table.header.item>
                </x-table.header>
                <x-table.body>
                    @foreach($passkeys as $passkey)
                        <tr>
                            <x-table.body.item>
                                {{ $passkey->name }}
                            </x-table.body.item>

                            <x-table.body.item>
                                {{ $passkey->last_used_at?->diffForHumans() ?? __('passkeys::passkeys.not_used_yet') }}
                            </x-table.body.item>

                            <x-table.body.item>
                                <x-button.floating wire:click="deletePasskey({{ $passkey->id }})" size="sm" color="danger" loading="deletePasskey">
                                    <i class="icon-trash"></i>
                                </x-button.floating>
                            </x-table.body.item>
                        </tr>
                    @endforeach
                </x-table.body>
            </x-table>
        </ul>
    </div>
</div>

@include('auth::livewire.components.passkeys.partials.createScript')

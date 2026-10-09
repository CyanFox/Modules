<div class="space-y-4">
    @persist('account.tabs.api')
    <x-account::profile-tabs selected-tab="api"/>
    @endpersist
    <div class="space-y-4" wire:transition>
        <x-cf.card hook="account.api">
            <x-slot:title>
                @hook('account.api.title')
                <div class="flex justify-between items-center">
                    <span>{{ __('account::account.api') }}</span>
                    <div class="flex space-x-2">
                        @hook('account.api.title.buttons')
                        <x-button.floating size="sm" :tooltip="__('account::api.tooltips.create')"
                                           wire:click="showCreateAPIKey" loading="showCreateAPIKey">
                            <i class="icon-plus"></i>
                        </x-button.floating>
                        <x-button.floating size="sm" :tooltip="__('account::api.tooltips.docs')" color="info"
                                           target="_blank"
                                           :link="url('/api/docs')">
                            <i class="icon-book-open-text"></i>
                        </x-button.floating>

                        @shook('s.account.api.title.buttons')
                        @endhook
                    </div>
                </div>
                @endhook
            </x-slot:title>
            <x-table>
                <x-table.header>
                    @hook('account.api.header')
                    <x-table.header.item>
                        {{ __('account::api.name') }}
                    </x-table.header.item>
                    <x-table.header.item>
                        {{ __('account::api.permissions') }}
                    </x-table.header.item>
                    <x-table.header.item>
                        {{ __('account::api.last_used') }}
                    </x-table.header.item>
                    <x-table.header.item>
                        {{ __('messages.tables.created_at') }}
                    </x-table.header.item>
                    <x-table.header.item>
                        {{ __('messages.tables.actions') }}
                    </x-table.header.item>
                    @endhook
                </x-table.header>
                <x-table.body>
                    @foreach(auth()->user()->tokens as $token)
                        @php
                            $properties = json_decode($token->name);

                            if (data_get($properties, 'type') == 'native') {
                                continue;
                            }
                        @endphp
                        @hook('account.api.body')
                        <x-table.body.row>
                            <x-table.body.item>
                                {{ data_get($properties, 'name') }}
                            </x-table.body.item>
                            <x-table.body.item>
                                <span x-data x-tooltip.raw="{{ implode(', ', $token->abilities) }}">
                                    {{ str()->limit(implode(', ', $token->abilities), 200, preserveWords: true) }}
                                </span>
                            </x-table.body.item>
                            <x-table.body.item>
                                <x-core::human-date :date="$token->last_used_at"
                                                    :fallback="__('account::api.never_used')"/>
                            </x-table.body.item>
                            <x-table.body.item>
                                <x-core::human-date :date="$token->created_at"/>
                            </x-table.body.item>
                            <x-table.body.item>
                                @hook('account.api.actions')
                                <x-button.floating size="sm" color="danger"
                                                   wire:click="deleteApiKey('{{ $token->id }}')"
                                                   loading="deleteApiKey('{{ $token->id }}')">
                                    <i class="icon-trash"></i>
                                </x-button.floating>

                                @hook('s.account.api.actions')
                                @endhook
                            </x-table.body.item>
                        </x-table.body.row>
                        @endhook
                    @endforeach
                </x-table.body>
            </x-table>
        </x-cf.card>
    </div>
</div>

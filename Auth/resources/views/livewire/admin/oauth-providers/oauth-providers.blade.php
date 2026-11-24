<div class="space-y-4">
    @persist('admin.settings.tabs.modules.auth')
    <x-admin::settings-tabs selected-tab="module"/>
    @endpersist

    <x-cf.card>
        <x-slot:title>
            @hook('auth.admin.oauth_providers.title')
            <div class="flex justify-between">
                <span>{{ __('auth::admin.oauth_providers.tab_title') }}</span>
                <x-button.floating size="sm" :tooltip="__('auth::admin.oauth_providers.tooltips.create')"
                                   wire:click="$dispatch('openModal', {modalComponent: 'auth::components.modals.admin.create-oauth-provider'})"
                                   loading="openModal">
                    <i class="icon-plus"></i>
                </x-button.floating>
            </div>
            @endhook
        </x-slot:title>

        <x-table>
            <x-table.header>
                <x-table.header.item>
                    {{ __('auth::admin.oauth_providers.name') }}
                </x-table.header.item>
                <x-table.header.item>
                    {{ __('auth::admin.oauth_providers.discovery_url') }}
                </x-table.header.item>
                <x-table.header.item>
                    {{ __('auth::admin.oauth_providers.redirect_uri') }}
                </x-table.header.item>
                <x-table.header.item>
                    {{ __('messages.tables.actions') }}
                </x-table.header.item>
            </x-table.header>
            <x-table.body>
                @foreach(\Modules\Auth\Models\OAuthProvider::all() as $provider)
                    <x-table.body.row>
                        <x-table.body.item>
                            {{ $provider->name }}
                        </x-table.body.item>
                        <x-table.body.item>
                            {{ $provider->discovery_url }}
                        </x-table.body.item>
                        <x-table.body.item>
                            {{ route('oauth.callback', ['provider' => $provider->id]) }}
                        </x-table.body.item>
                        <x-table.body.item>
                            <div class="flex flex-row gap-1">
                                <x-button.floating size="sm"
                                                   wire:click="$dispatch('openModal', {modalComponent: 'auth::components.modals.admin.update-oauth-provider', arguments: { providerId: '{{ $provider->id }}' }})"
                                                   loading="openModal">
                                    <i class="icon-pen"></i>
                                </x-button.floating>
                                <x-button.floating size="sm" color="danger"
                                                   wire:click="deleteProvider('{{ $provider->id }}')"
                                                   loading="deleteProvider('{{ $provider->id }}')">
                                    <i class="icon-trash"></i>
                                </x-button.floating>
                            </div>
                        </x-table.body.item>
                    </x-table.body.row>
                @endforeach
            </x-table.body>
        </x-table>
    </x-cf.card>
</div>

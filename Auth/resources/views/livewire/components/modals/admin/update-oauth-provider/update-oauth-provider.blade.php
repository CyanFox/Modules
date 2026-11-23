<div>
    <x-modal.header>
        {{ __('auth::admin.oauth_providers.update.title') }}
    </x-modal.header>

    <div class="p-4 space-y-4">
        <div class="grid md:grid-cols-2 gap-4">
            <x-input label="{{ __('auth::admin.oauth_providers.name') }}" wire:model="name" required/>
            <x-input label="{{ __('auth::admin.oauth_providers.discovery_url') }}" wire:model="discoveryUrl" required/>
            <x-input label="{{ __('auth::admin.oauth_providers.client_id') }}" wire:model="clientId" required/>
            <x-input label="{{ __('auth::admin.oauth_providers.client_secret') }}" wire:model="clientSecret" required/>
            <x-input label="{{ __('auth::admin.oauth_providers.icon') }}"
                     icon="{{ $icon ? (str_starts_with($icon, 'bi') ? $icon : 'icon-' . $icon) : null }}"
                     wire:model.live="icon">
                <x-slot:hint>
                    {!! Blade::render(__('auth::admin.oauth_providers.hints.icon')) !!}
                </x-slot:hint>
            </x-input>
            <x-select label="{{ __('auth::admin.oauth_providers.color') }}" wire:model.live="color">
                <option value=""></option>
                <option value="primary">{{ __('auth::admin.oauth_providers.colors.primary') }}</option>
                <option value="secondary">{{ __('auth::admin.oauth_providers.colors.secondary') }}</option>
                <option value="success">{{ __('auth::admin.oauth_providers.colors.success') }}</option>
                <option value="danger">{{ __('auth::admin.oauth_providers.colors.danger') }}</option>
                <option value="warning">{{ __('auth::admin.oauth_providers.colors.warning') }}</option>
                <option value="info">{{ __('auth::admin.oauth_providers.colors.info') }}</option>
                @if($color)
                    <x-slot:hint>
                        <div class="mt-1">
                            <x-badge
                                color="{{ $color }}">{{ __('auth::admin.oauth_providers.colors.' . $color) }}</x-badge>
                        </div>
                    </x-slot:hint>
                @endif
            </x-select>
        </div>
        <x-checkbox wire:model="accountLinking" label="{{ __('auth::admin.oauth_providers.account_linking') }}"
                    tooltip="{{ __('auth::admin.oauth_providers.tooltips.account_linking') }}"/>
        <x-divider/>
        <x-table>
            <x-table.header>
                <x-table.header.item>
                    {{ __('auth::admin.oauth_providers.oauth_field_name') }}
                </x-table.header.item>
                <x-table.header.item>
                    {{ __('auth::admin.oauth_providers.db_field_name') }}
                </x-table.header.item>
                <x-table.header.item>
                    {{ __('messages.tables.actions') }}
                </x-table.header.item>
            </x-table.header>
            <x-table.body>
                @foreach($fields as $index => $field)
                    <x-table.body.row wire:key="oauth-field-{{ $index }}">
                        <x-table.body.item>
                            <x-input wire:model="fields.{{ $index }}.oauth"/>
                        </x-table.body.item>
                        <x-table.body.item>
                            <x-input wire:model="fields.{{ $index }}.db"/>
                        </x-table.body.item>
                        <x-table.body.item>
                            <x-button.floating color="danger" size="sm" wire:click="deleteField('{{ $index }}')"
                                               loading="deleteField('{{ $index }}')">
                                <i class="icon-trash"></i>
                            </x-button.floating>
                        </x-table.body.item>
                    </x-table.body.row>
                @endforeach
            </x-table.body>
        </x-table>
        <x-button variant="outline" wire:click="addField" loading="addField">
            {{ __('auth::admin.oauth_providers.buttons.add_field') }}
        </x-button>
    </div>

    <x-modal.footer>
        <x-button type="submit" loading="updateOAuthProvider" wire:click="updateOAuthProvider">
            {{ __('auth::admin.oauth_providers.update.buttons.update') }}
        </x-button>
    </x-modal.footer>
</div>

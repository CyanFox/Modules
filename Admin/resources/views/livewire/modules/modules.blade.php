<div>
    <div class="mb-4">
        <x-card>
            <div class="flex md:justify-between md:flex-row flex-col">
                <x-button
                    wire:click="$dispatch('openModal', {component: 'admin::components.modals.install-module'})">
                    {{ __('admin::modules.buttons.install_module') }}
                </x-button>

                <x-view-integration name="admin.modules.header.install"/>

                <x-input wire:model="moduleSearchKeyword" wire:change="searchModule"
                         placeholder="{{ __('admin::modules.search') }}"/>

                <x-view-integration name="admin.modules.header"/>
            </div>
        </x-card>
    </div>

    <div class="mt-4 overflow-x-auto">
        <x-table>
            <x-table.header>
                <x-table.header.item>
                    {{ __('admin::modules.name') }}
                </x-table.header.item>
                <x-table.header.item>
                    {{ __('admin::modules.description') }}
                </x-table.header.item>
                <x-table.header.item>
                    {{ __('admin::modules.version') }}
                </x-table.header.item>
                <x-table.header.item>
                    {{ __('admin::modules.authors') }}
                </x-table.header.item>
                <x-table.header.item>
                    {{ __('admin::modules.status') }}
                </x-table.header.item>

                <x-view-integration name="admin.modules.table.header"/>
                <x-table.header.item>
                    {{ __('messages.tables.actions') }}
                </x-table.header.item>
            </x-table.header>
            <x-table.body>
                @foreach($moduleList as $module)
                    <tr>
                        <x-table.body.item>
                            {{ $module }}
                        </x-table.body.item>
                        <x-table.body.item>
                            {{ modules()->getDescription($module) }}
                        </x-table.body.item>
                        <x-table.body.item>
                            {{ modules()->getVersion($module) }}

                            @if(modules()->getRemoteVersion($module) != null && modules()->getVersion($module) != null &&
                                modules()->getVersion($module) != modules()->getRemoteVersion($module))
                                <x-badge color="warning">{{ __('admin::modules.update_available') }}</x-badge>
                            @endif
                        </x-table.body.item>
                        <x-table.body.item>
                            @if(is_array(modules()->getAuthors($module)))
                                {{ implode(',', modules()->getAuthors($module)) }}
                            @else
                                {{ modules()->getAuthors($module) }}
                            @endif
                        </x-table.body.item>
                        <x-table.body.item>
                            @if(modules()->getModule($module)->isDisabled())
                                <x-badge color="error">{{ __('admin::modules.disabled') }}</x-badge>
                            @else
                                <x-badge color="success">{{ __('admin::modules.enabled') }}</x-badge>
                            @endif
                        </x-table.body.item>

                        <x-view-integration name="admin.modules.table"/>

                        <x-table.body.item>
                            <x-button wire:click="deleteModule('{{ $module }}', false)"
                                      loading="deleteModule" class="px-2 py-1" color="danger">
                                <i class="icon-trash"></i>
                            </x-button>
                            @if(modules()->getModule($module)->isDisabled())
                                <x-button wire:click="enableModule('{{ $module }}')"
                                          loading="enableModule" class="px-2 py-1" color="success">
                                    <i class="icon-check"></i>
                                </x-button>
                            @else
                                <x-button wire:click="disableModule('{{ $module }}')"
                                          loading="deleteModule" class="px-2 py-1" color="warning">
                                    <i class="icon-ban"></i>
                                </x-button>
                            @endif
                        </x-table.body.item>
                    </tr>
                @endforeach
            </x-table.body>
        </x-table>
    </div>


    <x-view-integration name="admin.modules.end"/>
</div>

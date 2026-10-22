<div>
    <div wire:transition>
        <x-cf.card hook="admin.modules">
            @hook('admin.activity.table')

            <div class="space-y-4">
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
                        <x-table.header.item>
                            {{ __('messages.tables.actions') }}
                        </x-table.header.item>
                    </x-table.header>
                    <x-table.body>
                        @foreach(\Nwidart\Modules\Facades\Module::all() as $module)
                            <x-table.body.row>
                                <x-table.body.item>
                                    {{ $module->getName() }}
                                </x-table.body.item>
                                <x-table.body.item>
                                    {{ $module->getDescription() }}
                                </x-table.body.item>
                                <x-table.body.item>
                                    {{ $module->get('version') }}

                                    @if(modules()->getRemoteVersion($module->getName()) != null && modules()->getVersion($module->getName()) != null && modules()->getVersion($module->getName()) != modules()->getRemoteVersion($module->getName()))
                                        <x-badge
                                            color="warning">{{ __('admin::modules.update_available', ['remoteVersion' => modules()->getRemoteVersion($module->getName())]) }}</x-badge>
                                    @endif
                                </x-table.body.item>
                                <x-table.body.item>
                                    @if(is_array($module->get('authors')))
                                        {{ implode(', ', $module->get('authors')) }}
                                    @else
                                        {{ $module->get('authors') }}
                                    @endif
                                </x-table.body.item>
                                <x-table.body.item>
                                    @if($module->isDisabled())
                                        <x-badge color="danger">{{ __('admin::modules.disabled') }}</x-badge>
                                    @else
                                        <x-badge color="success">{{ __('admin::modules.enabled') }}</x-badge>
                                    @endif
                                </x-table.body.item>
                                <x-table.body.item class="flex gap-1">
                                    @if(modules()->getModule($module)->isDisabled())
                                        @can('admin.modules.enable')
                                            <x-button.floating wire:click="enableModule('{{ $module }}')"
                                                               :tooltip="__('admin::modules.tooltips.enable')"
                                                               loading="enableModule('{{ $module }}')" size="sm"
                                                               color="success">
                                                <i class="icon-check"></i>
                                            </x-button.floating>
                                        @endcan
                                    @else
                                        @can('admin.modules.disable')
                                            <x-button.floating wire:click="disableModule('{{ $module }}')"
                                                               :disabled="$module == 'Admin'"
                                                               :tooltip="__('admin::modules.tooltips.disable')"
                                                               loading="disableModule('{{ $module }}')" size="sm"
                                                               color="warning">
                                                <i class="icon-ban"></i>
                                            </x-button.floating>
                                        @endcan
                                    @endif
                                    @can('admin.modules.delete')
                                        <x-button.floating wire:click="deleteModule('{{ $module }}')"
                                                           :disabled="$module == 'Admin'"
                                                           :tooltip="__('admin::modules.tooltips.delete')"
                                                           loading="deleteModule('{{ $module }}')" size="sm"
                                                           color="danger">
                                            <i class="icon-trash"></i>
                                        </x-button.floating>
                                    @endcan
                                </x-table.body.item>
                            </x-table.body.row>
                        @endforeach
                    </x-table.body>
                </x-table>
            </div>
            @endhook
        </x-cf.card>
    </div>
</div>

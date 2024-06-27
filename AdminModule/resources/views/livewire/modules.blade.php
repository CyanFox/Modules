<div>
    <div class="mb-4">
        <x-card>
            <div class="flex md:justify-between md:flex-row flex-col">
                @can('adminmodule.modules.install')
                    <x-button
                        wire:click="$dispatch('toggleInstallModal')">
                        {{ __('adminmodule::modules.buttons.install_module') }}
                    </x-button>

                    <x-view-integration name="authmodule.modules.header"/>
                @endcan

                <x-input wire:model="moduleSearchKeyword" wire:change="searchModule"
                         placeholder="{{ __('adminmodule::modules.search') }}"/>
            </div>
        </x-card>
    </div>

    @if(count($moduleList) === 0)
        <x-card>
            <div class="text-2xl font-semibold text-center">
                {{ __('adminmodule::modules.no_modules') }}
            </div>
        </x-card>
    @endif

    <div class="flex flex-wrap gap-4 mt-4">
        @foreach($moduleList as $module)
            <div class="flex-grow">
                <x-card>
                    <div class="text-2xl font-semibold text-center">
                        {{ $module }}
                    </div>

                    <div class="text-center">
                        @if(module()->getModule($module)->isEnabled())
                            <x-badge color="green">
                                {{ __('adminmodule::modules.enabled') }}
                            </x-badge>
                        @else
                            <x-badge color="red">
                                {{ __('adminmodule::modules.disabled') }}
                            </x-badge>
                        @endif

                        <x-view-integration name="authmodule.modules.{{ $module }}.status"/>
                    </div>

                    <div class="flex mt-4 justify-between">
                        <div>
                            <x-dropdown position="bottom-start">
                                <x-slot:action>
                                    <x-button x-on:click="show = !show">
                                        <i class="icon-menu text-xl dark:text-white cursor-pointer"></i>
                                    </x-button>
                                </x-slot:action>
                                @if(module()->getModule($module)->getSettingsPage() !== null)
                                    @can('adminmodule.settings.view')
                                        <a href="{{ module()->getModule($module)->getSettingsPage() }}" wire:navigate>
                                            <x-dropdown.items>
                                                <i class="icon-settings text-md"></i>
                                                <span
                                                    class="ml-2 text-md">{{ __('adminmodule::modules.module_settings') }}</span>
                                            </x-dropdown.items>
                                        </a>
                                    @endcan
                                @endif

                                @can('adminmodule.modules.actions.migrate')
                                    <a wire:click="runMigrations('{{ $module }}')">
                                        <x-dropdown.items>
                                            <x-loading loading="runMigrations"/>
                                            <i class="icon-database text-md"></i>
                                            <span
                                                class="ml-2 text-md">{{ __('adminmodule::modules.run_migrations') }}</span>
                                        </x-dropdown.items>
                                    </a>
                                @endcan

                                @can('adminmodule.modules.actions.composer')
                                    <a wire:click="runComposer('{{ $module }}')">
                                        <x-dropdown.items>
                                            <x-loading loading="runComposer"/>
                                            <i class="icon-terminal text-md"></i>
                                            <span
                                                class="ml-2 text-md">{{ __('adminmodule::modules.run_composer') }}</span>
                                        </x-dropdown.items>
                                    </a>
                                @endcan

                                @can('adminmodule.modules.actions.npm')
                                    <a wire:click="runNpm('{{ $module }}')">
                                        <x-dropdown.items>
                                            <x-loading loading="runNpm"/>
                                            <i class="icon-terminal text-md"></i>
                                            <span class="ml-2 text-md">{{ __('adminmodule::modules.run_npm') }}</span>
                                        </x-dropdown.items>
                                    </a>
                                @endcan

                                <x-view-integration name="authmodule.modules.{{ $module }}.actions"/>
                            </x-dropdown>
                        </div>

                        <div>
                            @if(module()->getModule($module)->getSettingsPage() !== null)
                                @can('adminmodule.settings.view')
                                    <x-button :href="module()->getModule($module)->getSettingsPage()"><i
                                            class="icon-settings-2 text-lg"></i>
                                    </x-button>
                                @endcan
                            @endif

                            @can('adminmodule.modules.delete')
                                <x-button
                                    wire:click="deleteModule('{{ $module }}', false)">
                                    <i class="icon-trash text-lg text-red-600"></i>
                                </x-button>
                            @endcan

                            @if(module()->getModule($module)->isEnabled())
                                @can('adminmodule.modules.disable')
                                    <x-button wire:click="disableModule('{{ $module }}')"
                                              tooltip-bottom="{{ __('admin/modules.tooltip.disable_module') }}" spinner>
                                        <i class="icon-ban text-lg text-yellow-600"></i>
                                    </x-button>
                                @endcan
                            @else
                                @can('adminmodule.modules.enable')
                                    <x-button wire:click="enableModule('{{ $module }}')"
                                              tooltip-bottom="{{ __('admin/modules.tooltip.enable_module') }}" spinner>
                                        <i class="icon-check text-lg text-green-600"></i>
                                    </x-button>
                                @endcan
                            @endif


                            <x-view-integration name="authmodule.modules.{{ $module }}.buttons"/>
                        </div>
                    </div>
                </x-card>
            </div>
        @endforeach
    </div>

    @livewire('adminmodule::components.modals.install-module')
</div>

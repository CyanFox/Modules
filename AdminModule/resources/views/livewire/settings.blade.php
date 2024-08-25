<div>
    <x-tab wire:model.live="tab">
        <x-tab.items :tab="__('adminmodule::settings.tabs.system')">
            <x-slot:left>
                <i class="icon-settings"></i>
            </x-slot:left>
        </x-tab.items>

        <x-tab.items :tab="__('adminmodule::settings.tabs.modules')">
            <x-slot:left>
                <i class="icon-boxes"></i>
            </x-slot:left>
        </x-tab.items>

        @can('adminmodule.settings.editor')
            <x-tab.items :tab="__('adminmodule::settings.tabs.editor')">
                <x-slot:left>
                    <i class="icon-pen"></i>
                </x-slot:left>
            </x-tab.items>
        @endcan

        <x-view-integration name="authmodule.settings.tabs"/>
    </x-tab>

    <div class="mt-4">

        @if($tab == __('adminmodule::settings.tabs.system'))
            <x-card>
                <form wire:submit="updateSystemSettings">
                    @csrf

                    <x-view-integration name="authmodule.settings.system.from.header"/>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-5">
                        <x-input label="{{ __('adminmodule::settings.app_name') }} *" wire:model="systemName"/>
                        <x-input label="{{ __('adminmodule::settings.app_url') }} *" wire:model="systemUrl"/>

                        <x-select.styled label="{{ __('adminmodule::settings.app_lang') }} *" :options="[
                            ['label' => __('adminmodule::settings.languages.en'), 'value' => 'en'],
                            ['label' => __('adminmodule::settings.languages.de'), 'value' => 'de']]"
                                         select="label:label|value:value" wire:model="systemLang" searchable/>

                        <x-select.styled label="{{ __('adminmodule::settings.app_timezone') }} *"
                                         :options="$availableTimeZones"
                                         select="label:label|value:value" wire:model="systemTimeZone" searchable/>

                    </div>

                    <x-divider/>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 my-5">
                        <x-input label="{{ __('adminmodule::settings.unsplash_utm') }}"
                                 wire:model="unsplashUtm"/>
                        <x-password label="{{ __('adminmodule::settings.unsplash_api_key') }}"
                                    wire:model="unsplashApiKey"/>
                    </div>

                    <x-divider/>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 my-5">
                        <x-input label="{{ __('adminmodule::settings.project_version_url') }}" type="url"
                                 wire:model="projectVersionUrl"/>
                        <x-input label="{{ __('adminmodule::settings.template_version_url') }}" type="url"
                                 wire:model="templateVersionUrl"/>

                        <x-upload label="{{ __('adminmodule::settings.logo') }}" wire:model="logoFile" accept="image/png"/>
                    </div>

                    <x-divider/>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 my-5">
                        <x-select.styled label="{{ __('adminmodule::settings.enable_telemetry') }} *" :options="[
                            ['label' => __('messages.yes'), 'value' => '1'],
                            ['label' => __('messages.no'), 'value' => '0']]"
                                         select="label:label|value:value" wire:model="enableTelemetry" searchable/>

                        <x-input label="{{ __('adminmodule::settings.telemetry_url') }}"
                                 wire:model="telemetryUrl"/>
                    </div>

                    <x-view-integration name="authmodule.settings.system.form.footer"/>

                    @can('adminmodule.settings.update')
                        <x-divider/>

                        <div class="space-x-1 mt-3">
                            <x-button type="submit" loading="updateSystemSettings">
                                {{ __('messages.buttons.update') }}
                            </x-button>

                            <x-button wire:click="resetLogo" color="red" loading>
                                {{ __('adminmodule::settings.buttons.reset_logo') }}
                            </x-button>

                            <x-view-integration name="authmodule.settings.system.buttons"/>
                        </div>
                    @endcan

                    <x-view-integration name="authmodule.settings.system.footer"/>
                </form>
            </x-card>
        @endif

        @if($tab == __('adminmodule::settings.tabs.modules'))
            <x-card>
                <x-input label="{{ __('adminmodule::settings.search') }}" wire:model="moduleSearchKeyword"
                         wire:change="searchModule"/>

                <x-view-integration name="authmodule.settings.modules.title"/>
            </x-card>

            <div class="flex flex-wrap gap-4 mt-4">
                @php
                    $hasSettingsPage = false;
                @endphp

                @foreach($moduleList as $module)
                    @if(module()->getModule($module)->getSettingsPage())
                        <a href="{{ module()->getModule($module)->getSettingsPage() }}" class="flex-grow" wire:navigate>
                            <x-card>
                                <div class="flex justify-center items-center">
                                    <i class="icon-settings text-2xl"></i>
                                    <span class="ml-2">{{ $module }}</span>

                                    <x-view-integration name="authmodule.settings.modules.{{ $module }}"/>
                                </div>
                            </x-card>
                        </a>

                        @php
                            $hasSettingsPage = true;
                        @endphp
                    @endif
                @endforeach
            </div>

            @if (!$hasSettingsPage)
                <x-card>
                    <div class="text-center">
                        {{ __('adminmodule::settings.no_settings_page') }}

                        <x-view-integration name="authmodule.settings.modules.no_settings_page"/>
                    </div>
                </x-card>
            @endif
        @endif

        @if($tab == __('adminmodule::settings.tabs.editor'))
            <x-card>

                <div class="mb-4">
                    <x-input label="{{ __('adminmodule::settings.search') }}" wire:model="editorSearchKeyword"
                             wire:change="searchEditorSetting"/>

                    <x-view-integration name="authmodule.settings.editor.title"/>
                </div>

                <x-divider/>

                <x-view-integration name="authmodule.settings.editor.header"/>

                <form wire:submit="cryptEditorSetting('encrypt')" class="flex flex-row gap-3 my-4">
                    <div class="w-full">
                        <x-input label="{{ __('adminmodule::settings.editor.encrypt') }}"
                                 wire:model="editorEncryptionKeyword"/>
                    </div>

                    <div>
                        <x-button type="submit" class="mt-5" loading="cryptEditorSetting">
                            {{ __('adminmodule::settings.editor.buttons.encrypt') }}
                        </x-button>
                    </div>

                    <x-view-integration name="authmodule.settings.editor.encrypt"/>
                </form>

                <form wire:submit="cryptEditorSetting('decrypt')" class="flex flex-row gap-3 my-4">
                    <div class="w-full">
                        <x-input label="{{ __('adminmodule::settings.editor.decrypt') }}"
                                 wire:model="editorDecryptionKeyword"/>
                    </div>
                    <div>
                        <x-button type="submit" class="mt-5" loading="cryptEditorSetting">
                            {{ __('adminmodule::settings.editor.buttons.decrypt') }}
                        </x-button>
                    </div>

                    <x-view-integration name="authmodule.settings.editor.decrypt"/>
                </form>

                <x-divider/>

                <form wire:submit="updateEditorSettings">
                    <div class="space-y-3 my-4 overflow-x-auto">
                        @foreach($originalEditorSettings as $key => $value)
                            <div class="flex flex-row gap-3 my-4">
                                <div class="w-full">
                                    @if ($value['is_locked'])
                                        <x-input
                                            prefix="{{ $key }}:"
                                            wire:model="editorSettings.{{ str_replace('.', ':', $key) }}"
                                            disabled/>
                                    @else
                                        <x-input
                                            prefix="{{ $key }}:"
                                            wire:model="editorSettings.{{ str_replace('.', ':', $key) }}"/>
                                    @endif
                                </div>

                                <div>
                                    @if($value['is_locked'])
                                        <x-button color="red" class="mt-0.5" wire:click="setLockState('{{ $key }}', false)"
                                                  loading flat>
                                            <i class="icon-lock"></i>
                                        </x-button>
                                    @else
                                        <x-button color="green" class="mt-0.5" wire:click="setLockState('{{ $key }}', true)"
                                                  loading flat>
                                            <i class="icon-lock-open"></i>
                                        </x-button>
                                    @endif
                                </div>

                                <x-view-integration name="authmodule.settings.editor.encrypt"/>
                            </div>

                            <x-view-integration name="authmodule.settings.editor.{{ $key }}"/>
                        @endforeach

                        <x-view-integration name="authmodule.settings.editor.settings"/>
                    </div>

                    @can('adminmodule.settings.editor.update')
                        <x-divider/>

                        <div class="space-x-1 mt-3">
                            <x-button type="submit" loading="updateEditorSettings">
                                {{ __('messages.buttons.update') }}
                            </x-button>

                            <x-view-integration name="authmodule.settings.editor.buttons"/>
                        </div>
                    @endcan

                    <x-view-integration name="authmodule.settings.editor.footer"/>
                </form>

            </x-card>
        @endif
    </div>
</div>

<div>
    <div class="mb-4">
        <x-tab wire:model="tab">
            <x-tab.item class="flex-1 flex items-center justify-center" uuid="general"
                        wire:click="$set('tab', 'general')">
                <i class="icon-house"></i>
                <span class="ml-2">{{ __('admin::settings.tabs.general') }}</span>
            </x-tab.item>

            @can('admin.settings.modules')
                <x-tab.item class="flex-1 flex items-center justify-center" uuid="modules"
                            wire:click="$set('tab', 'modules')">
                    <i class="icon-package"></i>
                    <span class="ml-2">{{ __('admin::settings.tabs.modules') }}</span>
                </x-tab.item>
            @endcan

            @can('admin.settings.editor')
                <x-tab.item class="flex-1 flex items-center justify-center" uuid="editor"
                            wire:click="$set('tab', 'editor')">
                    <i class="icon-pen"></i>
                    <span class="ml-2">{{ __('admin::settings.tabs.editor') }}</span>
                </x-tab.item>
            @endcan

            <x-view-integration name="admin.settings.tabs"/>
        </x-tab>
    </div>

    @if($tab === 'general')
        <x-cf.card view-integration="admin.settings.general">
            <form wire:submit="updateGeneralSettings">
                <div class="grid lg:grid-cols-4 md:grid-cols-2 gap-4">
                    <x-input wire:model="appName" required>
                        {{ __('admin::settings.app_name') }}
                    </x-input>
                    <x-input wire:model="appUrl" required>
                        {{ __('admin::settings.app_url') }}
                    </x-input>
                    <x-select :label="__('admin::settings.app_timezone')" wire:model="appTimezone" required>
                        @foreach(timezone_identifiers_list() as $timezone)
                            <option value="{{ $timezone }}">{{ $timezone }}</option>
                        @endforeach
                    </x-select>
                    <x-select :label="__('admin::settings.app_language')" wire:model="appLanguage" required>
                        <option value="en">{{ __('admin::settings.languages.en') }}</option>
                        <option value="de">{{ __('admin::settings.languages.de') }}</option>
                        <x-view-integration name="admin.settings.general.languages"/>
                    </x-select>
                </div>

                <div class="grid md:grid-cols-2 gap-4 mt-4">
                    <x-input wire:model="baseVersionUrl" required>
                        {{ __('admin::settings.base_version_url') }}
                    </x-input>
                    <x-file wire:model="logo">
                        {{ __('admin::settings.logo') }}
                    </x-file>
                </div>

                @can('admin.settings.update')
                    <x-cf.buttons.update :show-cancel="false" :update-text="__('messages.buttons.save')"
                                         target="updateGeneralSettings" class="mt-0">
                        <x-button type="button" wire:click="resetLogo" loading="resetLogo" color="danger" class="ml-2">
                            {{ __('admin::settings.buttons.reset_logo') }}
                        </x-button>
                    </x-cf.buttons.update>
                @endcan
            </form>
        </x-cf.card>
    @endif
    @if($tab === 'modules' && auth()->user()->can('admin.settings.modules'))
        <x-card>
            <x-input wire:model="moduleSearch"
                     wire:change="searchModule">
                {{ __('admin::settings.search') }}
            </x-input>

            <x-view-integration name="admin.settings.modules.search"/>
        </x-card>

        <div class="flex flex-wrap gap-4 mt-4">
            @php
                $hasSettingsPage = false;
            @endphp

            @foreach($moduleList as $module)
                @if(modules()->getSettingsPage($module) !== null)
                    <a href="{{ modules()->getSettingsPage($module) }}" class="grow" wire:navigate>
                        <x-card>
                            <div class="flex justify-center items-center">
                                <i class="icon-settings text-2xl"></i>
                                <span class="ml-2">{{ $module }}</span>

                                <x-view-integration name="admin.settings.modules.{{ $module }}"/>
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
                    {{ __('admin::settings.no_settings_page') }}

                    <x-view-integration name="admin.settings.modules.no_settings_page"/>
                </div>
            </x-card>
        @endif
    @endif
    @if($tab === 'editor' && auth()->user()->can('admin.settings.editor'))
        <x-card>
            <div class="mb-4">
                <x-input wire:model="editorSearch"
                         wire:change="searchEditorSetting">
                    {{ __('admin::settings.search') }}
                </x-input>

                <x-view-integration name="admin.settings.editor.search"/>
            </div>

            <x-divider/>

            <x-view-integration name="admin.settings.editor.header"/>

            <form wire:submit="cryptEditorSetting('encrypt')" class="flex flex-row gap-3 my-4">
                <div class="w-full">
                    <x-input wire:model="editorEncryption">
                        {{ __('admin::settings.encrypt') }}
                    </x-input>
                </div>

                <div class="mt-auto mb-0.5">
                    <x-button type="submit" loading="cryptEditorSetting">
                        {{ __('admin::settings.buttons.encrypt') }}
                    </x-button>
                </div>

                <x-view-integration name="admin.settings.editor.encrypt"/>
            </form>

            <form wire:submit="cryptEditorSetting('decrypt')" class="flex flex-row gap-3 my-4">
                <div class="w-full">
                    <x-input wire:model="editorDecryption">
                        {{ __('admin::settings.decrypt') }}
                    </x-input>
                </div>
                <div class="mt-auto mb-0.5">
                    <x-button type="submit" loading="cryptEditorSetting">
                        {{ __('admin::settings.buttons.decrypt') }}
                    </x-button>
                </div>

                <x-view-integration name="admin.settings.editor.decrypt"/>
            </form>

            <x-divider/>

            <form wire:submit="createSetting" class="flex flex-row gap-3 my-4">
                <div class="w-full">
                    <x-input wire:model="newSettingKey">
                        {{ __('admin::settings.key') }}
                    </x-input>
                </div>
                <div class="w-full">
                    <x-input wire:model="newSettingValue">
                        {{ __('admin::settings.value') }}
                    </x-input>
                </div>

                <div class="mt-auto mb-0.5">
                    <x-button type="submit" loading="createSetting">
                        {{ __('admin::settings.buttons.create') }}
                    </x-button>
                </div>

                <x-view-integration name="admin.settings.editor.key_value"/>
            </form>

            <x-divider/>

            <form wire:submit="updateEditorSettings">
                <div class="space-y-3 my-4 overflow-x-auto">
                    @foreach($originalEditorSettings as $key => $value)
                        <div class="flex flex-row gap-3 my-4">
                            <div class="w-full">
                                @if ($value['is_locked'])
                                    <x-input
                                        wire:model="editorSettings.{{ str_replace('.', ':', $key) }}"
                                        disabled>
                                        {{ $key }}
                                    </x-input>
                                @else
                                    <x-input
                                        wire:model="editorSettings.{{ str_replace('.', ':', $key) }}">
                                        {{ $key }}
                                    </x-input>
                                @endif
                            </div>

                            @can('admin.settings.update')
                                <div class="mt-auto flex gap-3">
                                    @if($value['is_locked'])
                                        <x-button color="danger" variant="outline" type="button" class="mt-0.5"
                                                  wire:click="setLockState('{{ $key }}', false)"
                                                  loading="setLockState">
                                            <i class="icon-lock"></i>
                                        </x-button>
                                    @else
                                        <x-button color="success" variant="outline" type="button" class="mt-0.5"
                                                  wire:click="setLockState('{{ $key }}', true)"
                                                  loading="setLockState">
                                            <i class="icon-lock-open"></i>
                                        </x-button>
                                    @endif
                                    <x-button color="danger" variant="outline" type="button" class="mt-0.5"
                                              wire:click="deleteSetting('{{ $key }}', false)"
                                              loading="deleteSetting">
                                        <i class="icon-trash"></i>
                                    </x-button>
                                </div>
                            @endcan

                            <x-view-integration name="admin.settings.editor.encrypt"/>
                        </div>

                        <x-view-integration name="admin.settings.editor.{{ $key }}"/>
                    @endforeach

                    <x-view-integration name="admin.settings.editor.settings"/>
                </div>

                <x-divider/>

                @can('admin.settings.update')
                    <div class="space-x-1 mt-3">
                        <x-button type="submit" loading="updateEditorSettings">
                            {{ __('messages.buttons.save') }}
                        </x-button>

                        <x-view-integration name="admin.settings.editor.buttons"/>
                    </div>
                @endcan

                <x-view-integration name="admin.settings.editor.footer"/>
            </form>

        </x-card>
    @endif
</div>

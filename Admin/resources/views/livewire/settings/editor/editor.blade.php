@php use Modules\Core\Enums\SettingsProperty;use Modules\Core\Models\Setting; @endphp
<div>
    <div class="space-y-4" wire:transition>
        @persist('admin.settings.tabs.editor')
        <x-admin::settings-tabs selected-tab="editor"/>
        @endpersist

        <x-cf.card hook="admin.settings.editor">
            <form wire:submit="updateSettings">
                @php
                    $settingsByModule = Setting::all()->sortBy([
                        fn ($setting) => $setting->getProperty('module') ?? '',
                        fn ($setting) => $setting->key,
                    ])->groupBy(fn ($setting) => $setting->getProperty('module') ?: __('admin::settings.editor.no_module'));
                @endphp

                <div class="space-y-4">
                    @foreach($settingsByModule as $module => $settings)
                        <div class="mb-2 text-lg font-semibold {{ $loop->first ? '' : 'mt-6' }}">
                            {{ $module }}
                        </div>

                        <x-accordion selected-accordion-item="none">
                            @foreach($settings as $setting)
                                <x-accordion.item :uuid="$setting->key">
                                    <x-accordion.item.trigger>
                                        <div class="flex justify-between w-full">
                                            <div class="overflow-x-auto">
                                                @hook('admin.settings.editor.key')
                                                @if($setting->hasProperty(SettingsProperty::LANG_KEY))
                                                    <span x-data
                                                          x-tooltip.raw="{{ $setting->key }}">{{ __($setting->getProperty(SettingsProperty::LANG_KEY)) }}
                                                    </span>
                                                @else
                                                    {{ $setting->key }}
                                                @endif
                                                @shook('s.admin.settings.editor.key')
                                                @endhook
                                            </div>
                                            <div class="space-x-2">
                                                @hook('admin.settings.editor.icons')
                                                @if($setting->hasProperty(SettingsProperty::ENCRYPTED) && $setting->getProperty(SettingsProperty::ENCRYPTED))
                                                    <i class="icon-key-round" x-data
                                                       x-tooltip.raw="{{ __('admin::settings.editor.tooltips.encrypted') }}"></i>
                                                @endif
                                                @if($setting->hasProperty(SettingsProperty::INTERNAL) && $setting->getProperty(SettingsProperty::INTERNAL))
                                                    <i class="icon-lock" x-data
                                                       x-tooltip.raw="{{ __('admin::settings.editor.tooltips.internal') }}"></i>
                                                @endif
                                                @if($setting->hasProperty(SettingsProperty::AUTH) && $setting->getProperty(SettingsProperty::AUTH))
                                                    <i class="icon-user-round-key" x-data
                                                       x-tooltip.raw="{{ __('admin::settings.editor.tooltips.auth') }}"></i>
                                                @endif
                                                @if($setting->hasProperty(SettingsProperty::HIDDEN) && $setting->getProperty(SettingsProperty::HIDDEN))
                                                    <i class="icon-eye-off" x-data
                                                       x-tooltip.raw="{{ __('admin::settings.editor.tooltips.hidden') }}"></i>
                                                @endif
                                                @shook('s.admin.settings.editor.icons')
                                                @endhook
                                            </div>
                                            @shook('s.admin.settings.editor.header')
                                        </div>
                                    </x-accordion.item.trigger>
                                    <x-accordion.item.content>
                                        @hook('admin.settings.editor.types')
                                        @switch($setting->getProperty(SettingsProperty::TYPE))
                                            @case('int')
                                                <x-input type="number" step="1"
                                                         wire:model="settings.{{ $setting->id }}.value"
                                                         :tooltip="$setting->hasProperty(SettingsProperty::LANG_KEY) ? $setting->key : ''"
                                                         label="{{ $setting->hasProperty(SettingsProperty::LANG_KEY) ? __($setting->getProperty(SettingsProperty::LANG_KEY)) : $setting->key }}"/>
                                                @break
                                            @case('float')
                                                <x-input type="number" step="0.01"
                                                         wire:model="settings.{{ $setting->id }}.value"
                                                         :tooltip="$setting->hasProperty(SettingsProperty::LANG_KEY) ? $setting->key : ''"
                                                         label="{{ $setting->hasProperty(SettingsProperty::LANG_KEY) ? __($setting->getProperty(SettingsProperty::LANG_KEY)) : $setting->key }}"/>
                                                @break
                                            @case('bool')
                                                <x-checkbox wire:model="settings.{{ $setting->id }}.value"
                                                            :tooltip="$setting->hasProperty(SettingsProperty::LANG_KEY) ? $setting->key : ''"
                                                            label="{{ $setting->hasProperty(SettingsProperty::LANG_KEY) ? __($setting->getProperty(SettingsProperty::LANG_KEY)) : $setting->key }}"/>
                                                @break
                                            @default
                                                <x-textarea wire:model="settings.{{ $setting->id }}.value"
                                                            :tooltip="$setting->hasProperty(SettingsProperty::LANG_KEY) ? $setting->key : ''"
                                                            label="{{ $setting->hasProperty(SettingsProperty::LANG_KEY) ? __($setting->getProperty(SettingsProperty::LANG_KEY)) : $setting->key }}"/>
                                                @break
                                                @shook('s.admin.settings.editor.type')
                                        @endswitch
                                        @endhook

                                        <div class="flex flex-wrap gap-4 mt-4">
                                            @hook('admin.settings.editor.properties')
                                            <div class="w-full flex-1">
                                                <x-select :label="__('admin::settings.editor.type')"
                                                          wire:model="settings.{{ $setting->id }}.properties.type">
                                                    <option
                                                        value="string">{{ __('admin::settings.editor.types.string') }}</option>
                                                    <option
                                                        value="int">{{ __('admin::settings.editor.types.int') }}</option>
                                                    <option
                                                        value="float">{{ __('admin::settings.editor.types.float') }}</option>
                                                    <option
                                                        value="bool">{{ __('admin::settings.editor.types.bool') }}</option>
                                                    <option
                                                        value="array">{{ __('admin::settings.editor.types.array') }}</option>
                                                    <option
                                                        value="json">{{ __('admin::settings.editor.types.json') }}</option>
                                                </x-select>
                                            </div>
                                            <div class="w-full flex-1">
                                                <x-input :label="__('admin::settings.editor.module')"
                                                         wire:model="settings.{{ $setting->id }}.properties.module"/>
                                            </div>
                                            <div class="w-full flex-1">
                                                <x-select :label="__('admin::settings.editor.encrypted')"
                                                          wire:model="settings.{{ $setting->id }}.properties.encrypted">
                                                    <option value="0">{{ __('messages.no') }}</option>
                                                    <option value="1">{{ __('messages.yes') }}</option>
                                                </x-select>
                                            </div>
                                            <div class="w-full flex-1">
                                                <x-select :label="__('admin::settings.editor.internal')"
                                                          wire:model="settings.{{ $setting->id }}.properties.internal">
                                                    <option value="0">{{ __('messages.no') }}</option>
                                                    <option value="1">{{ __('messages.yes') }}</option>
                                                </x-select>
                                            </div>
                                            <div class="w-full flex-1">
                                                <x-select :label="__('admin::settings.editor.auth')"
                                                          wire:model="settings.{{ $setting->id }}.properties.auth">
                                                    <option value="0">{{ __('messages.no') }}</option>
                                                    <option value="1">{{ __('messages.yes') }}</option>
                                                </x-select>
                                            </div>
                                            <div class="w-full flex-1">
                                                <x-select :label="__('admin::settings.editor.hidden')"
                                                          wire:model="settings.{{ $setting->id }}.properties.hidden">
                                                    <option value="0">{{ __('messages.no') }}</option>
                                                    <option value="1">{{ __('messages.yes') }}</option>
                                                </x-select>
                                            </div>
                                            @shook('s.admin.settings.editor.properties')
                                            @endhook
                                        </div>

                                        <x-divider/>
                                        <x-cf.buttons :submit-text="__('admin::settings.buttons.update')"
                                                      target="updateSettings">
                                            <x-button color="danger" class="w-fit"
                                                      wire:click="deleteSetting('{{ $setting->id }}')"
                                                      loading="deleteSetting('{{ $setting->id }}')">
                                                {{ __('admin::settings.editor.buttons.delete') }}
                                            </x-button>
                                        </x-cf.buttons>
                                    </x-accordion.item.content>
                                </x-accordion.item>
                            @endforeach
                        </x-accordion>
                    @endforeach
                </div>

                <x-cf.buttons :submit-text="__('admin::settings.buttons.update')" target="updateSettings"/>
            </form>
        </x-cf.card>
    </div>
</div>

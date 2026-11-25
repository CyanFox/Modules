@php use Modules\Core\Enums\SettingsProperty; @endphp
<div class="space-y-4">
    @persist('account.tabs.settings')
    <x-account::profile-tabs selected-tab="settings"/>
    @endpersist
    <x-cf.card hook="account.settings">
        <form wire:submit="updateSettings">
            @php
                $settingsByModule = auth()->user()->userSettings()->get()
                    ->reject(fn ($setting) => $setting->hasProperty(SettingsProperty::HIDDEN) && $setting->getProperty(SettingsProperty::HIDDEN))
                    ->sortBy([
                    fn ($setting) => $setting->getProperty('module') ?? '',
                    fn ($setting) => $setting->key,
                ])->groupBy(fn ($setting) => $setting->getProperty('module') ?: __('account::settings.no_module'));
            @endphp

            @if(filled($settingsByModule))
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
                                                @hook('account.settings.key')
                                                @if($setting->hasProperty(SettingsProperty::LANG_KEY))
                                                    <span x-data
                                                          x-tooltip.raw="{{ $setting->key }}">{{ __($setting->getProperty(SettingsProperty::LANG_KEY)) }}
                                                    </span>
                                                @else
                                                    {{ $setting->key }}
                                                @endif
                                                @shook('s.account.settings.key')
                                                @endhook
                                            </div>
                                            @shook('s.account.settings.header')
                                        </div>
                                    </x-accordion.item.trigger>
                                    <x-accordion.item.content>
                                        @hook('account.settings.types')
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
                                                @shook('s.account.settings.type')
                                        @endswitch
                                        @endhook

                                        <x-divider/>
                                        <x-cf.buttons :submit-text="__('account::settings.buttons.update')"
                                                      target="updateSettings">
                                            <x-button color="danger" class="w-fit"
                                                      wire:click="deleteSetting('{{ $setting->id }}')"
                                                      loading="deleteSetting('{{ $setting->id }}')">
                                                {{ __('account::settings.buttons.delete') }}
                                            </x-button>
                                        </x-cf.buttons>
                                    </x-accordion.item.content>
                                </x-accordion.item>
                            @endforeach
                        </x-accordion>
                    @endforeach
                </div>

                <x-cf.buttons :submit-text="__('account::settings.buttons.update_all')" target="updateSettings"/>
            @else
                <div class="flex justify-center items-center">
                    <span>{{ __('account::settings.no_settings') }}</span>
                </div>
            @endif
        </form>
    </x-cf.card>
</div>

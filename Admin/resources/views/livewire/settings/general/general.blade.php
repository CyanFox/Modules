<div>
    <div class="space-y-4" wire:transition>
        @persist('admin.settings.tabs.general')
        <x-admin::settings-tabs selected-tab="general"/>
        @endpersist

        <x-cf.card hook="admin.settings.general">
            <form wire:submit="updateSettings">
                <div class="grid md:grid-cols-2 gap-4">
                    <x-input wire:model="name" :label="__('admin::settings.general.name')" required/>
                    <x-input wire:model="url" type="url" :label="__('admin::settings.general.url')" required/>
                </div>
                <div class="grid md:grid-cols-3 gap-4 mt-4">
                    <x-select :label="__('admin::settings.general.timezone')" wire:model="timezone" required>
                        @foreach(timezone_identifiers_list() as $timezone)
                            <option value="{{ $timezone }}">{{ $timezone }}</option>
                        @endforeach
                    </x-select>
                    <x-select :label="__('admin::settings.general.default_language')" wire:model="language" required>
                        <option value="en">{{ __('messages.languages.en') }}</option>
                        <option value="de">{{ __('messages.languages.de') }}</option>
                        <option value="de-sie">{{ __('messages.languages.de_sie') }}</option>
                        @shook('s.global.languages')
                    </x-select>
                    <x-file :label="__('admin::settings.general.logo')" wire:model="logo"/>
                </div>

                <x-cf.buttons :submit-text="__('admin::settings.buttons.update')" target="updateSettings">
                    <x-button wire:click="resetLogo" loading="resetLogo" color="danger">
                        {{ __('admin::settings.general.buttons.reset_logo') }}
                    </x-button>
                </x-cf.buttons>
            </form>
        </x-cf.card>
    </div>
</div>

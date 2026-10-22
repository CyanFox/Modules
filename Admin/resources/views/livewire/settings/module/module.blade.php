<div>
    <div wire:transition>
        @persist('admin.settings.tabs.module')
        <x-admin::settings-tabs selected-tab="module"/>
        @endpersist

        <div class="flex flex-wrap gap-4 mt-4">
            @php
                $hasSettingsPage = false;
            @endphp

            @foreach(\Nwidart\Modules\Facades\Module::all() as $module)
                @if(modules()->getSettingsPage($module) !== null)
                    <a href="{{ modules()->getSettingsPage($module) }}" class="grow" wire:navigate>
                        <x-cf.card hook="admin.settings.module">
                            <div class="flex justify-center items-center">
                                <i class="icon-settings text-2xl"></i>
                                <span class="ml-2">{{ $module }}</span>
                            </div>
                        </x-cf.card>
                    </a>

                    @php
                        $hasSettingsPage = true;
                    @endphp
                @endif
            @endforeach
        </div>

        @if(!$hasSettingsPage)
            <x-card>
                <div class="text-center">
                    {{ __('admin::settings.module.no_settings_page') }}
                    @shook('s.admin.settings.module.no_settings_page')
                </div>
            </x-card>
        @endif
    </div>
</div>

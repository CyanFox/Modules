<x-tab selected-tab="{{ $selectedTab }}">
    @hook('admin.settings.tabs')
    <x-tab.item class="flex-1 flex items-center justify-center" uuid="general" :href="route('admin.settings')"
                wire:navigate>
        <i class="icon-house"></i>
        <span class="ml-2">{{ __('admin::settings.general.tab_title') }}</span>
    </x-tab.item>
    <x-tab.item class="flex-1 flex items-center justify-center" uuid="module" :href="route('admin.settings.module')"
                wire:navigate>
        <i class="icon-boxes"></i>
        <span class="ml-2">{{ __('admin::settings.module.tab_title') }}</span>
    </x-tab.item>
    <x-tab.item class="flex-1 flex items-center justify-center" uuid="editor" :href="route('admin.settings.editor')"
                wire:navigate>
        <i class="icon-pen"></i>
        <span class="ml-2">{{ __('admin::settings.editor.tab_title') }}</span>
    </x-tab.item>

    @shook('s.admin.settings.tabs')
    @endhook
</x-tab>

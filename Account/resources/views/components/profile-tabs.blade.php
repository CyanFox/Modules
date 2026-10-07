<x-tab selected-tab="{{ $selectedTab }}">
    @hook('account.tabs')
    <x-tab.item class="flex-1 flex items-center justify-center" uuid="profile" :href="route('account.profile')"
                wire:navigate>
        <i class="icon-user"></i>
        <span class="ml-2">{{ __('account::account.profile') }}</span>
    </x-tab.item>
    <x-tab.item class="flex-1 flex items-center justify-center" uuid="sessions" :href="route('account.sessions')"
                wire:navigate>
        <i class="icon-monitor-dot"></i>
        <span class="ml-2">{{ __('account::account.sessions') }}</span>
    </x-tab.item>
    <x-tab.item class="flex-1 flex items-center justify-center" uuid="activity" :href="route('account.activity')"
                wire:navigate>
        <i class="icon-eye"></i>
        <span class="ml-2">{{ __('account::account.activity') }}</span>
    </x-tab.item>
    <x-tab.item class="flex-1 flex items-center justify-center min-w-fit" uuid="api" :href="route('account.api')"
                wire:navigate>
        <i class="icon-key"></i>
        <span class="ml-2">{{ __('account::account.api') }}</span>
    </x-tab.item>

    @shook('s.account.tabs')
    @endhook
</x-tab>

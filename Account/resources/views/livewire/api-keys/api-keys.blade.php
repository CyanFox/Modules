<div class="space-y-4">
    @persist('account.tabs.api')
    <x-account::profile-tabs selected-tab="api"/>
    @endpersist
    <div class="space-y-4" wire:transition>
        <x-card>

        </x-card>
    </div>
</div>

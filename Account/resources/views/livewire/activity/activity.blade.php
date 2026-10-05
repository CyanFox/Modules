<div class="space-y-4">
    @persist('account.tabs.activity')
    <x-account::profile-tabs selected-tab="activity"/>
    @endpersist
    <div class="space-y-4" wire:transition>
        <x-card>

        </x-card>
    </div>
</div>

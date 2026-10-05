<div class="space-y-4">
    @persist('account.tabs.sessions')
    <x-account::profile-tabs selected-tab="sessions"/>
    @endpersist
    <div class="space-y-4" wire:transition>
        <x-card>

        </x-card>
        <x-card>

        </x-card>
    </div>
</div>

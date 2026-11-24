<div class="space-y-4">
    @persist('account.tabs.activity')
    <x-account::profile-tabs selected-tab="activity"/>
    @endpersist
    <div class="space-y-4" wire:transition>
        <x-cf.card :title="__('account::account.activity')" hook="account.activity">
            @hook('account.activity.table')
            <x-pengutable :configuration="$this"/>
            @endhook
        </x-cf.card>
    </div>
</div>

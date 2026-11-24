<div>
    <div wire:transition>
        <x-cf.card hook="admin.users">
            @hook('admin.users.table')
            <x-pengutable :configuration="$this"/>
            @endhook
        </x-cf.card>
    </div>
</div>

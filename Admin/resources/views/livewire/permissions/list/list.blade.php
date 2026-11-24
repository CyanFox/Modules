<div>
    <div wire:transition>
        <x-cf.card hook="admin.permissions">
            @hook('admin.permissions.table')
            <x-pengutable :configuration="$this"/>
            @endhook
        </x-cf.card>
    </div>
</div>

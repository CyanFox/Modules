<div>
    <div wire:transition>
        <x-cf.card hook="admin.roles">
            @hook('admin.roles.table')
            <x-pengutable :configuration="$this"/>
            @endhook
        </x-cf.card>
    </div>
</div>

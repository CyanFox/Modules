<div>
    <div wire:transition>
        <x-cf.card hook="admin.activity">
            @hook('admin.activity.table')
            <x-pengutable :configuration="$this"/>
            @endhook
        </x-cf.card>
    </div>
</div>

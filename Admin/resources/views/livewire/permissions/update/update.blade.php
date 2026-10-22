<div>
    <div wire:transition>
        <x-cf.card hook="admin.permissions.update">
            @hook('admin.permissions.update.form')
            <form wire:submit="updatePermission">
                <div class="grid md:grid-cols-2 gap-4">
                    <x-input wire:model="name" :label="__('admin::permissions.name')" required/>
                    <x-input wire:model="guardName" :label="__('admin::permissions.guard_name')"
                             :hint="__('admin::permissions.guard_name_hint')" required/>
                </div>

                <x-cf.buttons :submit-text="__('admin::permissions.update.buttons.update')" target="updatePermission"/>
            </form>
            @endhook
        </x-cf.card>
    </div>
</div>
